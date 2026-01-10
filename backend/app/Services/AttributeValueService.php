<?php

namespace App\Services;

use App\Http\Requests\StoreAttributeValueRequest;
use App\Http\Requests\UpdateAttributeValueRequest;
use App\Models\AttributeValue;

class AttributeValueService
{
    public function create(StoreAttributeValueRequest $request): AttributeValue
    {
        $data = $request->validated();

        return AttributeValue::create([
            'attribute_id' => $data['attribute_id'],
            'value' => $data['value'],
            'color_code' => $data['color_code'] ?? null,
        ]);
    }

    public function update(UpdateAttributeValueRequest $request, AttributeValue $attributeValue): AttributeValue
    {
        $data = $request->validated();

        $attributeValue->update([
            'attribute_id' => $data['attribute_id'],
            'value' => $data['value'],
            'color_code' => $data['color_code'] ?? null,
        ]);

        return $attributeValue;
    }

    public function deleteMany(array $ids): int
    {
        return AttributeValue::whereIn('id', $ids)->delete();
    }
}
