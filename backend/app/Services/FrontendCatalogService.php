<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class FrontendCatalogService
{
    public function getProducts(Request $request): LengthAwarePaginator
    {
        $perPage = $request->input('perPage') ?: 8;

        return Product::query()
            ->active()
            ->when($request->input('category_id'), function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->input('category_slug'), function ($query, $categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery->where('slug', $categorySlug);
                });
            })
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->input('sort'), function ($query, $sort) {
                $direction = strtoupper((string) $sort) === 'ASC' ? 'ASC' : 'DESC';
                $query->orderBy('unit_price', $direction);
            }, function ($query) {
                $query->orderByDesc('id');
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getCategories(Request $request): LengthAwarePaginator
    {
        $perPage = $request->input('perPage') ?: 8;

        return Category::query()
            ->active()
            ->select(['id', 'name', 'slug', 'image', 'status'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getProductBySlug(string $slug): Product
    {
        $product = Product::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $product->load([
            'variants.attributes.attribute',
            'variants.attributes.attributeValue',
            'attributes.attribute',
            'attributes.attributeValue',
            'category',
        ]);

        return $product;
    }
}
