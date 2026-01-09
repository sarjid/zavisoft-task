<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $perPage = $request->input('perPage') ?: 8;
        $categories = Product::query()
            ->select(['id', 'name', 'slug', 'thumbnail', 'status'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return ProductResource::collection($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $product = DB::transaction(function () use ($request, $data) {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = file_upload($request->file('thumbnail'), 'uploads/products', 'product');
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = file_upload($image, 'uploads/products', 'product');
                }
            }

            $product = Product::create([
                'category_id' => $data['category_id'] ?? null,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'thumbnail' => $thumbnailPath,
                'images' => $imagePaths ?: null,
                'unit_price' => $data['unit_price'],
                'sku' => $data['sku'] ?? null,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? 'fixed',
                'description' => $data['description'] ?? null,
                'current_stock' => $data['current_stock'] ?? 0,
                'status' => $data['status'] ?? StatusEnum::INACTIVE->value,
            ]);

            $this->replaceVariations($product, $data['variations'] ?? [], $request);

            return $product;
        });

        return $this->successResponse([
            'product' => $product->load(['category', 'variants.attributes.attribute', 'variants.attributes.attributeValue']),
        ], 'Product created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $product = DB::transaction(function () use ($request, $data, $product) {
            $thumbnailPath = $product->thumbnail;
            if ($request->hasFile('thumbnail')) {
                delete_public_file($product->thumbnail);
                $thumbnailPath = file_upload($request->file('thumbnail'), 'uploads/products', 'product');
            }

            $imagePaths = $product->images ?? [];
            if ($request->hasFile('images')) {
                foreach ($product->images ?? [] as $imagePath) {
                    delete_public_file($imagePath);
                }
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = file_upload($image, 'uploads/products', 'product');
                }
            }

            $product->update([
                'category_id' => $data['category_id'] ?? $product->category_id,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'thumbnail' => $thumbnailPath,
                'images' => $imagePaths ?: null,
                'unit_price' => $data['unit_price'],
                'sku' => $data['sku'] ?? null,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? 'fixed',
                'description' => $data['description'] ?? null,
                'current_stock' => $data['current_stock'] ?? 0,
                'status' => $data['status'] ?? $product->status,
            ]);

            if (array_key_exists('variations', $data)) {
                $this->replaceVariations($product, $data['variations'] ?? [], $request);
            }

            return $product;
        });

        return $this->successResponse([
            'product' => $product->load(['category', 'variants.attributes.attribute', 'variants.attributes.attributeValue']),
        ], 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    private function replaceVariations(Product $product, array $variations, Request $request): void
    {
        $existingVariants = $product->variants()->get(['id', 'image']);
        foreach ($existingVariants as $variant) {
            delete_public_file($variant->image);
        }
        $product->variants()->delete();

        foreach ($variations as $index => $variation) {
            $variationImage = null;
            $variationFile = $request->file("variations.{$index}.image");
            if ($variationFile) {
                $variationImage = file_upload($variationFile, 'uploads/products/variations', 'variant');
            }

            $variant = $product->variants()->create([
                'sku' => $variation['sku'] ?? null,
                'price' => $variation['price'] ?? null,
                'quantity' => $variation['quantity'] ?? 0,
                'image' => $variationImage,
            ]);

            $attributes = [];
            foreach ($variation['attributes'] ?? [] as $attribute) {
                $attributes[] = [
                    'attribute_id' => $attribute['attribute_id'],
                    'attribute_value_id' => $attribute['attribute_value_id'],
                ];
            }

            if ($attributes) {
                $variant->attributes()->createMany($attributes);
            }
        }
    }
}
