<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /*
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'categories'
        ));
    }*/
        
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('category');

        $reviews = $product->reviews()
        ->with('user')
        ->latest()
        ->get();

        return view('products.show', compact('product', 'reviews'));
    }

    
public function index(Request $request): View
{
    $filters = $request->validate([
        'search' => ['nullable', 'string', 'max:100'],
        'category' => ['nullable', 'string', 'exists:categories,slug'],
    ]);

    $query = Product::with('category')
        ->where('is_active', true)
        ->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        })
        ->when($filters['category'] ?? null, function ($query, $slug) {
            $query->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        });

    // Default sorting option.
    $sort = trim((string) $request->query('sort', 'created_at DESC'));

    if ($sort === '') {
        $sort = 'created_at DESC';
    }

    // Raw input
    $query->orderByRaw($sort);

    /*
    $allowedSorts = [
        'created_at DESC' => ['created_at', 'desc'],
        'price ASC'       => ['price', 'asc'],
        'price DESC'      => ['price', 'desc'],
        'name ASC'        => ['name', 'asc'],
    ];

    [$column, $direction] = $allowedSorts[$sort]
        ?? $allowedSorts['created_at DESC'];

    $query->orderBy($column, $direction);
    */

    $products = $query->paginate(12)->withQueryString();

    $categories = Category::orderBy('name')->get();

    return view('products.index', compact(
        'products',
        'categories',
        'sort'
    ));
}

    
}
