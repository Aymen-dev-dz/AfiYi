<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository
{
    public function getActive(int $perPage = 16, ?string $search = null, ?string $category = null, bool $featured = false): LengthAwarePaginator
    {
        $query = Product::active()->with('seller');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($featured) {
            $query->featured();
        }

        return $query->latest()->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::active()
            ->where('slug', $slug)
            ->with(['seller'])
            ->first();
    }

    public function getRelated(Product $product, int $limit = 4): Collection
    {
        return Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit($limit)
            ->get();
    }

    public function getCategories(): Collection
    {
        return Product::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();
    }

    public function getRecommendedForMood(array $emotionalTags, int $limit = 4): Collection
    {
        $query = Product::active();

        if (!empty($emotionalTags)) {
            $query->where(function ($q) use ($emotionalTags) {
                foreach ($emotionalTags as $tag) {
                    $q->orWhere('description', 'like', "%{$tag}%")
                      ->orWhere('name', 'like', "%{$tag}%")
                      ->orWhere('category', 'like', "%{$tag}%");
                }
            });
        }

        return $query->inRandomOrder()->limit($limit)->get();
    }
}
