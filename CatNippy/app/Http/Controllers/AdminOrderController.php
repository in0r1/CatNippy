<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->is_admin, 403);

        $orders = Order::latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }
}