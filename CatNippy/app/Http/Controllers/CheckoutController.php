<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    private const FREE_SHIPPING_THRESHOLD_CENTS = 10000;
    private const SHIPPING_COST_CENTS = 590;

    public function index(Request $request)
    {
        $cart = $this->buildCart($request);

        if ($cart['problem']) {
            return redirect()
                ->route('cart.index')
                ->with('error', $cart['problem']);
        }

        $subtotalCents = $cart['subtotal_cents'];

        $shippingCents = $subtotalCents >= self::FREE_SHIPPING_THRESHOLD_CENTS
            ? 0
            : self::SHIPPING_COST_CENTS;

        return view('checkout.index', [
            'items' => $cart['items'],
            'subtotal' => $subtotalCents / 100,
            'shipping' => $shippingCents / 100,
            'total' => ($subtotalCents + $shippingCents) / 100,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'payment_outcome' => ['required', 'in:success,failure'],
        ]);

        $cart = $this->buildCart($request);

        if ($cart['problem']) {
            return redirect()
                ->route('cart.index')
                ->with('error', $cart['problem']);
        }

        // Simulated failure: do not create an order or clear the cart.
        if ($validated['payment_outcome'] === 'failure') {
            return redirect()
                ->route('checkout.index')
                ->withInput()
                ->with('error', 'The demo payment was declined. Your bag has been preserved.');
        }

        $subtotalCents = $cart['subtotal_cents'];

        $shippingCents = $subtotalCents >= self::FREE_SHIPPING_THRESHOLD_CENTS
            ? 0
            : self::SHIPPING_COST_CENTS;

        $totalCents = $subtotalCents + $shippingCents;

        // Only create the order after the simulated payment succeeds.
        $order = DB::transaction(function () use (
            $validated,
            $cart,
            $subtotalCents,
            $shippingCents,
            $totalCents
        ) {
            $order = Order::create([
                'order_number' => 'CN-' . now()->format('ymd') . '-'
                    . Str::upper(Str::random(8)),
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
                'currency' => 'EUR',
                'subtotal' => number_format($subtotalCents / 100, 2, '.', ''),
                'shipping_cost' => number_format($shippingCents / 100, 2, '.', ''),
                'total' => number_format($totalCents / 100, 2, '.', ''),
                'status' => 'processing',
                'payment_status' => 'paid',
            ]);

            foreach ($cart['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_slug' => $item['product']->slug,
                    'unit_price' => number_format(
                        $item['unit_price_cents'] / 100,
                        2,
                        '.',
                        ''
                    ),
                    'quantity' => $item['quantity'],
                    'line_total' => number_format(
                        $item['line_total_cents'] / 100,
                        2,
                        '.',
                        ''
                    ),
                ]);
            }

            return $order;
        });
        // Store a simple invoice for the customer's order.
        $order->load('items');

        $invoiceLines = [
            'CatNippy - Order Receipt',
            'Order number: ' . $order->order_number,
            'Date: ' . $order->created_at->format('Y-m-d H:i:s'),
            'Customer: ' . $order->customer_name,
            'Email: ' . $order->email,
            '',
            'Items:',
        ];

        foreach ($order->items as $item) {
            $invoiceLines[] = sprintf(
                '%d x %s — EUR %s',
                $item->quantity,
                $item->product_name,
                number_format((float) $item->line_total, 2, '.', '')
            );
        }

        $invoiceLines[] = '';
        $invoiceLines[] = 'Subtotal: EUR ' . number_format((float) $order->subtotal, 2, '.', '');
        $invoiceLines[] = 'Shipping: EUR ' . number_format((float) $order->shipping_cost, 2, '.', '');
        $invoiceLines[] = 'Total: EUR ' . number_format((float) $order->total, 2, '.', '');

        Storage::disk('local')->put(
            'invoices/' . $order->order_number . '.txt',
            implode(PHP_EOL, $invoiceLines) . PHP_EOL
        );
        // Clear the bag only after the order transaction succeeds.
        $request->session()->forget('cart');
        $request->session()->put('last_order_id', $order->id);

        return redirect()->route('checkout.confirmation');
    }

    public function confirmation(Request $request)
    {
        $orderId = $request->session()->get('last_order_id');

        $order = Order::with('items')->find($orderId);

        if (
            !$order ||
            ($order->user_id !== null && (int) $order->user_id !== (int) auth()->id())
        ) {
            return redirect()->route('home');
        }

        return view('checkout.confirmation', compact('order'));
    }

    /**
     * Rebuild the cart using current database products and prices.
     * Never trust prices or totals supplied by the browser.
     */
    
    private function buildCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        if (!is_array($cart) || empty($cart)) {
            return [
                'items' => [],
                'subtotal_cents' => 0,
                'problem' => 'Your bag is empty. Add a product before checking out.',
            ];
        }

        $quantities = [];

        foreach ($cart as $id => $item) {
            // CartController stores items as ['quantity' => 1].
            if (
                !is_numeric($id) ||
                !is_array($item) ||
                !isset($item['quantity']) ||
                !is_numeric($item['quantity'])
            ) {
                continue;
            }

            $id = (int) $id;
            $quantity = (int) $item['quantity'];

            if ($id > 0 && $quantity >= 1 && $quantity <= 99) {
                $quantities[$id] = $quantity;
            }
        }

        if (empty($quantities)) {
            return [
                'items' => [],
                'subtotal_cents' => 0,
                'problem' => 'Your bag has no valid items. Please review it.',
            ];
        }

        $products = Product::whereIn('id', array_keys($quantities))
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($quantities)) {
            return [
                'items' => [],
                'subtotal_cents' => 0,
                'problem' => 'One or more products are unavailable. Please review your bag.',
            ];
        }

        $items = [];
        $subtotalCents = 0;

        foreach ($quantities as $id => $quantity) {
            $product = $products->get($id);

            if (!$product || !$product->is_active) {
                return [
                    'items' => [],
                    'subtotal_cents' => 0,
                    'problem' => 'One or more products are no longer available.',
                ];
            }

            $unitPriceCents = (int) round((float) $product->price * 100);
            $lineTotalCents = $unitPriceCents * $quantity;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price_cents' => $unitPriceCents,
                'line_total_cents' => $lineTotalCents,
            ];

            $subtotalCents += $lineTotalCents;
        }

        return [
            'items' => $items,
            'subtotal_cents' => $subtotalCents,
            'problem' => null,
        ];
    }

}
