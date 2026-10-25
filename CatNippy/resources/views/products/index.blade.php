@extends('layouts.app')

@section('title', 'Shop — CatNippy')

@section('content')
    <section class="container hero">
        <p class="text-uppercase small muted fw-semibold">
            From zoomies to snoozies 
        </p>

        <h1 class="display-4 fw-bold">
            We've got<br>the Purrfect stuff covered.
        </h1>

        <p class="muted mt-3">
            Premium catnip, toys, and daily comforts your cat will actually use.
        </p>
    </section>

    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Shop all products</h2>

            <span class="muted small">
                {{ $products->total() }} products
            </span>
        </div>

        
    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label for="search" class="form-label">Search products</label>
                <input
                    id="search"
                    type="search"
                    name="search"
                    class="form-control"
                    placeholder="Search products..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="col-12 col-md-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select">
                    <option value="">All categories</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->slug }}"
                            @selected(request('category') === $category->slug)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label for="sort" class="form-label">Sort by</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="created_at DESC"
                        @selected($sort === 'created_at DESC')>
                        Newest
                    </option>

                    <option value="price ASC"
                        @selected($sort === 'price ASC')>
                        Price: low to high
                    </option>

                    <option value="price DESC"
                        @selected($sort === 'price DESC')>
                        Price: high to low
                    </option>

                    <option value="name ASC"
                        @selected($sort === 'name ASC')>
                        Name: A–Z
                    </option>
                </select>
            </div>

            <div class="col-12 col-md-2 d-grid">
                <button type="submit" class="btn btn-dark">
                    Apply
                </button>
            </div>
        </div>
    </form>


        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="{{ route('products.show', $product->slug) }}">
                        <article class="product-card">
                            <div class="product-placeholder">
                                Product image coming soon
                            </div>

                            <div class="card-body">
                                <p class="muted small mb-2">
                                    {{ $product->category?->name }}
                                </p>

                                <h3 class="h6 fw-semibold">
                                    {{ $product->name }}
                                </h3>

                                <p class="muted small">
                                    {{ \Illuminate\Support\Str::limit(
                                        $product->description, 75
                                    ) }}
                                </p>

                                <p class="fw-bold mb-0">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>
                        </article>
                    </a>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <h3>No products yet</h3>
                    <p class="muted">
                        Check back soon for our latest collection.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </section>
@endsection