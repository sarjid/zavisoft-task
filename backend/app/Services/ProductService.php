<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(StoreProductRequest $request): Product
    {
        $data = $request->validated();

        return DB::transaction(function () use ($request, $data) {
            $variationStock = $this->calculateVariationStock($data['variations'] ?? []);
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
                'current_stock' => $variationStock ?? ($data['current_stock'] ?? 0),
                'status' => $data['status'] ?? StatusEnum::INACTIVE->value,
            ]);

            $this->replaceVariations($product, $data['variations'] ?? [], $request);

            return $product;
        });
    }

    public function update(UpdateProductRequest $request, Product $product): Product
    {
        $data = $request->validated();

        return DB::transaction(function () use ($request, $data, $product) {
            $variationStock = $this->calculateVariationStock($data['variations'] ?? []);
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
                'current_stock' => $variationStock ?? ($data['current_stock'] ?? 0),
                'status' => $data['status'] ?? $product->status,
            ]);

            if (array_key_exists('variations', $data)) {
                $this->replaceVariations($product, $data['variations'] ?? [], $request);
            }

            return $product;
        });
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

    private function calculateVariationStock(array $variations): ?int
    {
        if (!count($variations)) {
            return null;
        }
        return array_reduce($variations, function ($total, $variation) {
            return $total + (int) ($variation['quantity'] ?? 0);
        }, 0);
    }
}
