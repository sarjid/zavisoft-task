<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $perPage = $request->input('perPage') ?: 8;
        $products = Product::query()
            ->withCount('variants')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return ProductResource::collection($products);
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

        return $this->successResponse([
            'product' => $product->load(['category', 'variants.attributes.attribute', 'variants.attributes.attributeValue']),
        ], 'Product created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
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

    public function multipleDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        $products = Product::with('variants')->whereIn('id', $data['ids'])->get(['id', 'thumbnail', 'images']);
        foreach ($products as $product) {
            delete_public_file($product->thumbnail);
            foreach ($product->images ?? [] as $imagePath) {
                delete_public_file($imagePath);
            }
            foreach ($product->variants as $variant) {
                delete_public_file($variant->image);
            }
        }

        $deleted = Product::whereIn('id', $data['ids'])->delete();

        return $this->successResponse([
            'deleted' => $deleted,
        ], 'Products deleted successfully');
    }

    public function changeStatus(Request $request, Product $product)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(StatusEnum::values())],
        ]);

        $product->update([
            'status' => $data['status'],
        ]);

        return $this->successResponse([
            'id' => $product->id,
            'status' => $product->status,
        ]);
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


    public function editorFileUpload(Request $request)
    {
        if (!$request->hasFile('upload')) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'No file uploaded',
                ],
            ], 400);
        }

        $filePath = file_upload($request->file('upload'), 'uploads/editor', 'editor');

        return response()->json([
            'uploaded' => true,
            'url' => $filePath,
        ], 200);
    }

    public function createData()
    {
        $categories = Category::query()
            ->active()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $attributes = Attribute::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return $this->successResponse([
            'categories' => $categories,
            'attributes' => $attributes,
        ]);
    }

    public function attributeValues(Request $request)
    {
        $attributeIds = $request->input('attribute_ids', []);
        if (is_string($attributeIds)) {
            $attributeIds = array_filter(explode(',', $attributeIds));
        }

        $attributeIds = array_values(array_filter((array) $attributeIds));

        $values = AttributeValue::query()
            ->when($attributeIds, function ($query) use ($attributeIds) {
                $query->whereIn('attribute_id', $attributeIds);
            })
            ->orderBy('value')
            ->get(['id', 'attribute_id', 'value', 'color_code'])
            ->groupBy('attribute_id')
            ->map(fn ($items) => $items->values());

        return $this->successResponse([
            'attribute_values' => $values,
        ]);
    }
}
