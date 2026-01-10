<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryListResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function products(Request $request)
    {
        $perPage = $request->input('perPage') ?: 8;
        $products = Product::query()
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

        return ProductResource::collection($products);
    }


    public function categories(Request $request)
    {
        $perPage = $request->input('perPage') ?: 8;

        $categories = Category::query()
            ->active()
            ->select(['id', 'name', 'slug', 'image', 'status'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return CategoryListResource::collection($categories);
    }

    public function product(string $slug)
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

        return $this->successResponse([
            'product' => new ProductResource($product),
        ]);
    }
}
