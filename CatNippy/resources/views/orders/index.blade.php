@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-2">My orders</h1>
    <p class="text-muted mb-4">View your previous CatNippy purchases.</p>

    @if ($orders->isEmpty())
        <div class="card">
            <div class="card-body p-4">
                <h2 class="h5">No orders yet</h2>
                <p class="text-muted">
                    Once you place an order while signed in,
                    it will appear here.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Browse products
                </a>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach ($orders as $order)
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between gap-3">
                            <div>
                                <h2 class="h5 mb-1">
                                    <a href="{{ route('orders.show', $order) }}">
                                        {{ $order->order_number }}
                                    </a>
                                </h2>

                                <p class="text-muted small mb-2">
                                    Placed {{ $order->created_at->format('d M Y, H:i') }}
                                </p>

                                <span class="badge bg-secondary">
                                    {{ ucfirst($order->status) }}
                                </span>

                                <span class="badge bg-success">
                                    Payment: {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>

                            <div class="text-end">
                                <div class="text-muted small">Order total</div>
                                <div class="fs-5 fw-bold">
                                    €{{ number_format((float) $order->total, 2) }}
                                </div>

                                <a href="{{ route('orders.show', $order) }}"
                                   class="btn btn-outline-primary btn-sm mt-2">
                                    View order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
