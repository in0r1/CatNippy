@extends('layouts.app')

@section('content')
<div class="container py-5">
    <a href="{{ route('orders.index') }}" class="text-decoration-none">
        &larr; Back to my orders
    </a>

    <div class="d-flex flex-wrap justify-content-between gap-3 mt-3 mb-4">
        <div>
            <h1 class="h2">Order {{ $order->order_number }}</h1>
            <p class="text-muted mb-0">
                Placed {{ $order->created_at->format('d M Y, H:i') }}
            </p>
            <a
                href="{{ route('orders.invoice', [
                    'order' => $order->id,
                    'file' => $order->order_number . '.txt',
                ]) }}"
                class="btn btn-outline-primary"
            >
                Download invoice
            </a>
        </div>

        <div class="text-end">
            <span class="badge bg-secondary">
                {{ ucfirst($order->status) }}
            </span>
            <div class="mt-2 text-muted small">Payment status</div>
            <strong>{{ ucfirst($order->payment_status) }}</strong>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Items ordered</h2>

            @foreach ($order->items as $item)
                <div class="d-flex justify-content-between gap-3 mb-3">
                    <div>
                        <div class="fw-semibold">{{ $item->product_name }}</div>
                        <div class="text-muted small">
                            Quantity: {{ $item->quantity }}
                            × €{{ number_format((float) $item->unit_price, 2) }}
                        </div>
                    </div>

                    <div class="text-nowrap">
                        €{{ number_format((float) $item->line_total, 2) }}
                    </div>
                </div>
            @endforeach

            <hr>

            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span>€{{ number_format((float) $order->subtotal, 2) }}</span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Shipping</span>
                <span>
                    {{ (float) $order->shipping_cost == 0
                        ? 'Free'
                        : '€' . number_format((float) $order->shipping_cost, 2) }}
                </span>
            </div>

            <hr>

            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total</span>
                <span>€{{ number_format((float) $order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Delivery details</h2>
            <p class="mb-1">{{ $order->customer_name }}</p>
            <p class="mb-1">{{ $order->email }}</p>

            @if ($order->phone)
                <p class="mb-1">{{ $order->phone }}</p>
            @endif

            <p class="mb-0">
                {{ $order->address }}<br>
                {{ $order->city }}, {{ $order->postal_code }}<br>
                {{ $order->country }}
            </p>
        </div>
    </div>
</div>
@endsection
