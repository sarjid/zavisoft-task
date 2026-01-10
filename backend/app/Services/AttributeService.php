<?php

namespace App\Services;

use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Models\Attribute;

class AttributeService
{
    public function create(StoreAttributeRequest $request): Attribute
    {
        $data = $request->validated();

        return Attribute::create([
            'name' => $data['name'],
        ]);
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute): Attribute
    {
        $data = $request->validated();

        $attribute->update([
            'name' => $data['name'],
        ]);

        return $attribute;
    }

    public function deleteMany(array $ids): int
    {
        return Attribute::whereIn('id', $ids)->delete();
    }
}
