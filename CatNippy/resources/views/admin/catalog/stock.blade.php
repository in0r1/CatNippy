@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <div class="small text-uppercase mb-2">
            CatNippy / CATALOG OPERATIONS
        </div>
        <h1 class="h2">Stock management</h1>
        <p class="text-secondary">
            Inventory availability and supplier synchronization.
        </p>
    </div>

    <div class="card bg-dark text-light border-secondary">
        <div class="card-header border-secondary d-flex justify-content-between">
            <span>Supplier availability</span>
            <span class="badge bg-secondary">External feed</span>
        </div>

        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="text-secondary small">Product SKU</div>
                    <h5>LR-1001 — Everyday Canvas Backpack</h5>
                    <p class="text-secondary mb-md-0">
                        Supplier stock synchronization
                    </p>
                </div>
                <div class="col-md-5 text-md-end">
                    <span id="sync-status" class="text-secondary">
                        Waiting for supplier response…
                    </span>
                </div>
            </div>

            <hr class="border-secondary">

            <div class="text-secondary small mb-2">Latest supplier response</div>
            <pre id="sync-result"
                 class="p-3 rounded bg-black text-light mb-3"
                 style="white-space:pre-wrap;overflow-wrap:anywhere">Synchronizing inventory…</pre>

            <button type="button" id="refresh-stock"
                    class="btn btn-outline-light">
                Refresh stock
            </button>
        </div>
    </div>
</div>

{{-- The browser submits these fields automatically; there is no URL textbox. --}}
<form id="stock-refresh-form"
      action="{{ route('admin.catalog.stock.refresh') }}"
      method="POST"
      hidden>
    @csrf
    <input type="hidden" name="sku" value="{{ $sku }}">
    <input type="hidden" name="feed_url" value="{{ $feedUrl }}">
</form>

<script>
(() => {
    const form = document.getElementById('stock-refresh-form');
    const status = document.getElementById('sync-status');
    const resultBox = document.getElementById('sync-result');
    const button = document.getElementById('refresh-stock');

    async function refreshStock() {
        status.textContent = 'Contacting supplier…';
        resultBox.textContent = 'Waiting for response…';
        button.disabled = true;

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

            status.textContent = data.ok
                ? `Supplier response received (HTTP ${data.http_status})`
                : `Supplier request failed (HTTP ${data.http_status ?? response.status})`;

            resultBox.textContent =
                data.preview ?? data.message ?? 'No response preview available.';
        } catch (error) {
            status.textContent = 'Supplier request failed';
            resultBox.textContent = 'Could not complete the stock refresh.';
        } finally {
            button.disabled = false;
        }
    }

    button.addEventListener('click', refreshStock);
    refreshStock();
})();
</script>
@endsection
