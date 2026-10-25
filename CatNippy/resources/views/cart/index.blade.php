@extends('layouts.app')

@section('title', 'Your bag — CatNippy')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h1>Your bag</h1>
        <p>Review your items before checkout.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($items->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <h2 class="mb-3">Your bag is empty</h2>
                <p>Find something you'll love in our collection.</p>
                <a href="{{ route('products.index') }}"
                   class="btn btn-primary mt-2">
                    Explore products
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-3">
                    @foreach ($items as $item)
                        <div class="card" key="{{ $item['product']->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between
                                            align-items-start gap-3 flex-wrap">
                                    <div>
                                        <h3 class="card-title mb-2">
                                            <a href="{{ route('products.show', $item['product']->slug) }}">
                                                {{ $item['product']->name }}
                                            </a>
                                        </h3>

                                        <p class="mb-2">
                                            ${{ number_format($item['product']->price, 2) }}
                                            each
                                        </p>
                                    </div>

                                    <form method="POST"
                                          action="{{ route('cart.destroy', $item['product']->slug) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-outline-secondary btn-sm">
                                            Remove
                                        </button>
                                    </form>
                                </div>

                                <div class="d-flex justify-content-between
                                            align-items-end flex-wrap gap-3 mt-3">
                                    <form method="POST"
                                          action="{{ route('cart.update', $item['product']->slug) }}"
                                          class="d-flex align-items-end gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <label class="form-label small"
                                                   for="quantity-{{ $item['product']->id }}">
                                                Quantity
                                            </label>

                                            <input
                                                id="quantity-{{ $item['product']->id }}"
                                                type="number"
                                                name="quantity"
                                                class="form-control"
                                                min="1"
                                                max="99"
                                                value="{{ $item['quantity'] }}"
                                                required
                                            >
                                        </div>

                                        <button type="submit"
                                                class="btn btn-outline-secondary">
                                            Update
                                        </button>
                                    </form>

                                    <div class="product-price">
                                        ${{ number_format($item['subtotal'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4">Order summary</h2>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>

                        <p class="small mb-4">
                            Shipping and any applicable taxes will be
                            calculated at checkout.
                        </p>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100">
                            Proceed to checkout

                        </a>

                        <a href="{{ route('products.index') }}"
                           class="btn btn-outline-secondary w-100 mt-2">
                            Continue shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
