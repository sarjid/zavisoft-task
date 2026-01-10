<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttributeValueRequest;
use App\Http\Requests\UpdateAttributeValueRequest;
use App\Http\Resources\AttributeValueResource;
use App\Models\AttributeValue;
use App\Services\AttributeValueService;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function __construct(private readonly AttributeValueService $attributeValueService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ?: 10;
        $attributeId = $request->input('attribute_id');

        $values = AttributeValue::query()
            ->with('attribute:id,name')
            ->when($attributeId, function ($query) use ($attributeId) {
                $query->where('attribute_id', $attributeId);
            })
            ->when($request->input('search'), function ($query, $search) {
                $query->where('value', 'like', "%{$search}%");
            })
            ->orderBy('value')
            ->paginate($perPage)
            ->withQueryString();

        return AttributeValueResource::collection($values);
    }

    public function store(StoreAttributeValueRequest $request)
    {
        $value = $this->attributeValueService->create($request);

        return $this->successResponse([
            'attribute_value' => new AttributeValueResource($value->load('attribute:id,name')),
        ], 'Attribute value created successfully', 201);
    }

    public function update(UpdateAttributeValueRequest $request, AttributeValue $attributeValue)
    {
        $attributeValue = $this->attributeValueService->update($request, $attributeValue);

        return $this->successResponse([
            'attribute_value' => new AttributeValueResource($attributeValue->load('attribute:id,name')),
        ], 'Attribute value updated successfully');
    }

    public function destroy(AttributeValue $attributeValue)
    {
        $attributeValue->delete();

        return $this->successResponse([
            'deleted' => true,
        ], 'Attribute value deleted successfully');
    }

    public function multipleDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:attribute_values,id'],
        ]);

        $deleted = $this->attributeValueService->deleteMany($data['ids']);

        return $this->successResponse([
            'deleted' => $deleted,
        ], 'Attribute values deleted successfully');
    }
}
