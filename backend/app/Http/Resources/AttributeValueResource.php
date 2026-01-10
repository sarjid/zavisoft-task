<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
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
            'attribute_id' => $this->attribute_id,
            'value' => $this->value,
            'color_code' => $this->color_code,
            'attribute' => $this->whenLoaded('attribute', function () {
                return [
                    'id' => $this->attribute->id,
                    'name' => $this->attribute->name,
                ];
            }),
        ];
    }
}
