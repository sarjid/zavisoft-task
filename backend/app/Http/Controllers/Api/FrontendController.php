<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryListResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Services\FrontendCatalogService;

class FrontendController extends Controller
{
    public function __construct(private readonly FrontendCatalogService $catalogService)
    {
    }

    public function products(Request $request)
    {
        $products = $this->catalogService->getProducts($request);

        return ProductResource::collection($products);
    }


    public function categories(Request $request)
    {
        $categories = $this->catalogService->getCategories($request);

        return CategoryListResource::collection($categories);
    }

    public function product(string $slug)
    {
        $product = $this->catalogService->getProductBySlug($slug);

        return $this->successResponse([
            'product' => new ProductResource($product),
        ]);
    }
}
