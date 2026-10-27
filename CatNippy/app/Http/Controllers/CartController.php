<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $products = Product::whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(
            function ($item, $id) use ($products) {
                $product = $products->get($id);

                if (!$product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
            }
        )->filter();

        $total = $items->sum('subtotal');

        return view('cart.index', compact('items', 'total'));
    }

    public function store(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $cart = $request->session()->get('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = ['quantity' => 1];
        }

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to your cart.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$product->id])) {
            return redirect()->route('cart.index');
        }

        $cart[$product->id]['quantity'] = $validated['quantity'];

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Your cart has been updated.');
    }

    public function destroy(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);

        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product removed from your cart.');
    }
}
