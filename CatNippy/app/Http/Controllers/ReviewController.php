<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(
        Request $request,
        Product $product
    ): RedirectResponse {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $product->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        return redirect()
            ->route('products.show', $product->slug)
            ->with('success', 'Your review has been submitted.');
    }
}