@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-2">Checkout</h1>
    <p class="text-muted mb-4">Your details and delivery information.</p>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            Please correct the highlighted form errors and try again.
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Contact information</h2>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label">
                                Full name
                            </label>
                            <input
                                id="customer_name"
                                name="customer_name"
                                type="text"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name') }}"
                                autocomplete="name"
                                required
                            >
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', auth()->user()?->email) }}"
                                autocomplete="email"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                Phone number <span class="text-muted">(optional)</span>
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Shipping address</h2>

                        <div class="mb-3">
                            <label for="address" class="form-label">Street address</label>
                            <input
                                id="address"
                                name="address"
                                type="text"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address') }}"
                                autocomplete="street-address"
                                required
                            >
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input
                                    id="city"
                                    name="city"
                                    type="text"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city') }}"
                                    autocomplete="address-level2"
                                    required
                                >
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">
                                    Postal code
                                </label>
                                <input
                                    id="postal_code"
                                    name="postal_code"
                                    type="text"
                                    class="form-control @error('postal_code') is-invalid @enderror"
                                    value="{{ old('postal_code') }}"
                                    autocomplete="postal-code"
                                    required
                                >
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input
                                id="country"
                                name="country"
                                type="text"
                                class="form-control @error('country') is-invalid @enderror"
                                value="{{ old('country') }}"
                                autocomplete="country-name"
                                required
                            >
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-2">Demo payment</h2>
                        <p class="text-muted small">
                            This is a local payment simulation. Do not enter real
                            payment details; no card information is collected.
                        </p>

                        <label for="payment_outcome" class="form-label">
                            Simulated payment result
                        </label>
                        <select
                            id="payment_outcome"
                            name="payment_outcome"
                            class="form-select @error('payment_outcome') is-invalid @enderror"
                            required
                        >
                            <option value="success"
                                @selected(old('payment_outcome', 'success') === 'success')>
                                Successful payment
                            </option>
                            <option value="failure"
                                @selected(old('payment_outcome') === 'failure')>
                                Declined payment
                            </option>
                        </select>
                        @error('payment_outcome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Place demo order
                </button>

                <a href="{{ route('cart.index') }}"
                   class="btn btn-outline-secondary w-100 mt-2">
                    Return to bag
                </a>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h2 class="h5 mb-4">Order summary</h2>

                    @foreach ($items as $item)
                        <div class="d-flex justify-content-between gap-3 mb-3">
                            <div>
                                <div class="fw-semibold">
                                    {{ $item['product']->name }}
                                </div>
                                <div class="text-muted small">
                                    Quantity: {{ $item['quantity'] }}
                                </div>
                            </div>

                            <div class="text-nowrap">
                                €{{ number_format($item['line_total_cents'] / 100, 2) }}
                            </div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>€{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>
                            {{ $shipping == 0 ? 'Free' : '€' . number_format($shipping, 2) }}
                        </span>
                    </div>

                    @if ($shipping == 0)
                        <p class="text-success small mb-3">
                            Your order qualifies for free shipping.
                        </p>
                    @else
                        <p class="text-muted small mb-3">
                            Shipping is free on orders of €100 or more.
                        </p>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>€{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
