<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use Illuminate\Http\Request;
use App\Services\AttributeService;

class AttributeController extends Controller
{
    public function __construct(private readonly AttributeService $attributeService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ?: 10;

        $includeValues = $request->boolean('include_values');

        $attributes = Attribute::query()
            ->withCount('values')
            ->when($includeValues, function ($query) {
                $query->with('values');
            })
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return AttributeResource::collection($attributes);
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
    public function store(StoreAttributeRequest $request)
    {
        $attribute = $this->attributeService->create($request);

        return $this->successResponse([
            'attribute' => new AttributeResource($attribute),
        ], 'Attribute created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $attribute = $this->attributeService->update($request, $attribute);

        return $this->successResponse([
            'attribute' => new AttributeResource($attribute->fresh()),
        ], 'Attribute updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return $this->successResponse([
            'deleted' => true,
        ], 'Attribute deleted successfully');
    }

    public function multipleDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:attributes,id'],
        ]);

        $deleted = $this->attributeService->deleteMany($data['ids']);

        return $this->successResponse([
            'deleted' => $deleted,
        ], 'Attributes deleted successfully');
    }
}
