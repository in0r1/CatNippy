<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
/*
    public function show(Request $request, Order $order)
    {
        // Customers can only view orders belonging to their own account.
        abort_unless(
            (int) $order->user_id === (int) $request->user()->id,
            404
        );

        $order->load('items');

        return view('orders.show', compact('order'));
    }*/
        
    public function show(Request $request, Order $order)
    {
        // Keep this behavior limited to local development.
        abort_unless(app()->environment('local'), 404);

        $order->load('items');

        return view('orders.show', compact('order'));
    }
}
