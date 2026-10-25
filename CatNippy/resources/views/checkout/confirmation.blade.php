@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mx-auto" style="max-width: 760px;">
        <div class="text-center mb-5">
            <h1>Thank you for your order!</h1>
            <p class="text-muted">
                Your payment succeeded and your order has been recorded.
            </p>
        </div>

        <div class="card mb-4">
            <div class="card-body p-4">
                <p class="mb-1 text-muted">Order number</p>
                <h2 class="h4">{{ $order->order_number }}</h2>

                <p class="mb-1 mt-4">
                    <strong>Confirmation email:</strong> {{ $order->email }}
                </p>
                <p class="mb-1">
                    <strong>Payment status:</strong> {{ ucfirst($order->payment_status) }}
                </p>
                <p class="mb-0">
                    <strong>Shipping to:</strong>
                    {{ $order->address }}, {{ $order->city }},
                    {{ $order->postal_code }}, {{ $order->country }}
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Order details</h2>

                @foreach ($order->items as $item)
                    <div class="d-flex justify-content-between gap-3 mb-3">
                        <div>
                            <div class="fw-semibold">{{ $item->product_name }}</div>
                            <div class="text-muted small">
                                {{ $item->quantity }} ×
                                €{{ number_format((float) $item->unit_price, 2) }}
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

        @guest
            <p class="text-muted">
                Checked out as a guest?
                <a href="{{ route('register') }}">Create an account</a>
                to get started with a CatNippy account.
            </p>
        @endguest

        <a href="{{ route('products.index') }}" class="btn btn-primary">
            Continue shopping
        </a>
    </div>
</div>
@endsection
