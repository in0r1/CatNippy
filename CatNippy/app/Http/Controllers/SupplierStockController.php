<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Throwable;

class SupplierStockController extends Controller
{
    private function authorizeLabAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeLabAdmin($request);

        return view('admin.catalog.stock', [
            'sku' => 'CN-1001',
            'feedUrl' => 'http://127.0.0.1:8765/stock?sku=CN-1001',
        ]);
    }

    public function refresh(Request $request)
    {
        $this->authorizeLabAdmin($request);

        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:40'],
            'feed_url' => ['required', 'string', 'url', 'max:2048'],
        ]);

        $feedUrl = $validated['feed_url'];
        $scheme = strtolower((string) parse_url($feedUrl, PHP_URL_SCHEME));

        if (!in_array($scheme, ['http', 'https'], true)) {
            return response()->json([
                'message' => 'Supplier feed request failed.',
            ], 422);
        }

        try {
            // the destination is accepted from the browser request.
            $response = Http::connectTimeout(2)
                ->timeout(4)
                ->withOptions(['allow_redirects' => false])
                ->get($feedUrl);

            return response()->json([
                'ok' => $response->successful(),
                'http_status' => $response->status(),
                'content_type' => $response->header('Content-Type')
                    ?? 'unknown',
                'preview' => $response->body(),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'The supplier service could not be reached.',
            ], 502);
        }
    }
}
