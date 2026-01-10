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
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

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
        $product = $this->productService->create($request);

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
        $product = $this->productService->update($request, $product);

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
