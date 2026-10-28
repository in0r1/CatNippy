<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class ProductAvailabilityController extends Controller
{
    public function check(
        \Illuminate\Http\Request $request,
        \App\Models\Product $product
    ): \Illuminate\Http\JsonResponse {
        abort_unless(app()->environment('local'), 404);
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'feed_url' => ['required', 'string', 'url', 'max:2048'],
        ]);

        $url = $validated['feed_url'];
        $parts = parse_url($url) ?: [];


        try {
            $upstream = \Illuminate\Support\Facades\Http::connectTimeout(2)
                ->timeout(4)
                ->withOptions(['allow_redirects' => false])
                ->get($url);

            return response()->json([
                'ok' => $upstream->successful(),
                'upstream_content_type' =>
                    $upstream->header('Content-Type') ?? '',
                'body' => mb_substr($upstream->body(), 0, 20000),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'The supplier service could not be reached.',
            ], 502);
        }
    }
}
