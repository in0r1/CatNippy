@extends('layouts.app')

@section('title', $product->name . ' — CatNippy')

@section('content')
    <section class="container py-5">
        <a href="{{ route('products.index') }}" class="muted small">
            &larr; Back to shop
        </a>

        <div class="row g-5 mt-2">
            <div class="col-md-6">
                <div class="product-placeholder rounded-4"
                     style="min-height: 420px;">
                    Product image coming soon
                </div>
            </div>

            <div class="col-md-6 py-md-4">
                <p class="muted small">
                    {{ $product->category?->name }}
                </p>

                <h1 class="display-5 fw-bold">
                    {{ $product->name }}
                </h1>

                <p class="fs-4 fw-semibold mt-3">
                    ${{ number_format($product->price, 2) }}
                </p>

                <p class="muted mt-4">
                    {{ $product->description }}
                </p>

                <p class="small mt-4">
                    @if ($product->stock > 0)
                        In stock
                    @else
                        Currently unavailable
                    @endif
                </p>

                <div class="card mt-3 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h6 mb-1">Supplier availability</h2>
                            <p class="muted small mb-0">
                                Latest supplier information
                            </p>
                        </div>

                        <span id="supplier-check-status"
                            class="muted small"
                            role="status"
                            aria-live="polite">
                            Checking…
                        </span>
                    </div>

                    <iframe
                        id="supplier-check-frame"
                        title="Supplier response"
                        class="mt-3"
                        hidden
                        sandbox=""
                        style="width:100%; height:300px; border:1px solid #444; border-radius:8px;"
                    ></iframe>
                </div>
            </div>

            <form id="supplier-check-form"
                action="{{ route('products.availability-check', ['product' => $product->slug]) }}"
                method="POST"
                hidden>
                @csrf

                <input
                    type="hidden"
                    name="feed_url"
                    value="{{ 'http://127.0.0.1:8766/stock?sku=' . urlencode($product->slug) }}"
                >
            </form>

            <script>
            (() => {
                const form = document.getElementById('supplier-check-form');
                const status = document.getElementById('supplier-check-status');
                const frame = document.getElementById('supplier-check-frame');

                async function checkSupplier() {
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(new FormData(form))
                        });

                        const data = await response.json();

                        if (!response.ok || !data.ok) {
                            status.textContent =
                                data.message || 'Supplier check failed';
                            frame.hidden = true;
                            return;
                        }

                        const contentType =
                            data.upstream_content_type || data.content_type || '';

                        const body = data.body ?? data.preview ?? '';

                        // Normal supplier response: display its stock number.
                        if (data.supplier_available !== undefined &&
                            data.supplier_available !== null) {
                            status.textContent =
                                `Supplier available: ${data.supplier_available}`;
                            frame.hidden = true;
                            frame.srcdoc = '';
                            return;
                        }

                        // JSON response containing supplier stock.
                        if (contentType.includes('application/json')) {
                            const result = JSON.parse(body);

                            if (result.available !== undefined) {
                                status.textContent =
                                    `Supplier available: ${result.available}`;
                                frame.hidden = true;
                                frame.srcdoc = '';
                                return;
                            }
                        }

                        // HTML response: display it inside the existing card.
                        if (contentType.includes('text/html')) {
                            status.textContent = 'Supplier information received';
                            frame.srcdoc = body;
                            frame.hidden = false;
                            return;
                        }

                        status.textContent = 'Supplier response unavailable';
                        frame.hidden = true;
                    } catch (error) {
                        status.textContent = 'Supplier check failed';
                        frame.hidden = true;
                    }
                }

                checkSupplier();
            })();
            </script>

                <form method="POST" action="{{ route('cart.store', $product->slug) }}">
                    @csrf
                    
                    <button type="submit" class="btn btn-primary">
                        Add to cart
                    </button>
                </form>
            </div>
        </div>
    </section>
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <h2 class="h3 mb-2">Customer reviews</h2>
            <p class="text-muted">
                Share your experience with this product.
            </p>

            @auth
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">Write a review</h3>

                        <form
                            method="POST"
                            action="{{ route('products.reviews.store', $product->slug) }}"
                        >
                            @csrf

                            <div class="mb-3">
                                <label for="rating" class="form-label">
                                    Rating
                                </label>

                                <select
                                    id="rating"
                                    name="rating"
                                    class="form-select"
                                    required
                                >
                                    <option value="">Choose a rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option
                                            value="{{ $i }}"
                                            @selected(old('rating') == $i)
                                        >
                                            {{ $i }} out of 5
                                        </option>
                                    @endfor
                                </select>

                                @error('rating')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="review-title" class="form-label">
                                    Review title
                                </label>

                                <input
                                    id="review-title"
                                    name="title"
                                    type="text"
                                    class="form-control"
                                    maxlength="120"
                                    value="{{ old('title') }}"
                                    required
                                >

                                @error('title')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="review-body" class="form-label">
                                    Your review
                                </label>

                                <textarea
                                    id="review-body"
                                    name="body"
                                    class="form-control"
                                    rows="4"
                                    maxlength="2000"
                                    required
                                >{{ old('body') }}</textarea>

                                @error('body')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Submit review
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <p class="mt-3">
                    <a href="{{ route('login') }}">Sign in</a>
                    to leave a review.
                </p>
            @endauth
        </div>

        <div class="col-lg-7">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($reviews as $review)
                <article class="card mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <h3 class="h5 mb-1">
                                    {{ $review->title }}
                                </h3>

                                <div class="text-warning mb-2"
                                     aria-label="{{ $review->rating }} out of 5 stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                            </div>

                            <small class="text-muted text-nowrap">
                                {{ $review->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <p class="text-muted small mb-2">
                            By {{ $review->user->name }}
                        </p>

                        <div class="review-body">
                            {!! $review->body !!}
                        </div>
                    </div>
                </article>
            @empty
                <div class="card">
                    <div class="card-body p-4 text-muted">
                        No reviews yet. Be the first to share your experience.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
