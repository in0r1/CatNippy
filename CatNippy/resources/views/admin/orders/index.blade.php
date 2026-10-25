@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Store orders</h1>
            <p class="text-muted mb-0">
                Review recent customer orders.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                <div class="small text-muted">
                                    {{ $order->email }}
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                {{ ucfirst($order->status) }}
                            </td>
                            <td class="text-end text-nowrap">
                                €{{ number_format((float) $order->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No orders have been placed yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection