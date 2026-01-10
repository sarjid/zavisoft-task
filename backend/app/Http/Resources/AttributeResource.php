<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'values_count' => $this->values_count ?? 0,
            'values' => $this->whenLoaded('values', function () {
                return $this->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'attribute_id' => $value->attribute_id,
                        'value' => $value->value,
                        'color_code' => $value->color_code,
                    ];
                });
            }),
        ];
    }
}
