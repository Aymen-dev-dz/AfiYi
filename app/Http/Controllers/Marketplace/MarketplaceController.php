<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('seller');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($needsParam = $request->input('needs')) {
            $needsArray = explode(',', $needsParam);
            $query->withAnyTag($needsArray);
        }

        if ($tagsParam = $request->input('tags')) {
            $tagsArray = explode(',', $tagsParam);
            $query->withAnyTag($tagsArray);
        }

        if ($request->input('featured')) {
            $query->featured();
        }

        $products = $query->latest()->paginate(16);

        $categories = Product::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('marketplace.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['seller'])
            ->firstOrFail();

        $related = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('product', 'related'));
    }
}
