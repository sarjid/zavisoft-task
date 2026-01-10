<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hasVariants = $this->relationLoaded('variants')
            ? $this->variants->isNotEmpty()
            : $this->variants()->exists();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail ? url($this->thumbnail) : null,
            'unit_price' => $this->unit_price,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'current_price' => $this->current_price,
            'has_variant' => (bool) $hasVariants,
            'description' => $this->whenLoaded('variants', $this->description),
            'variants' => $this->whenLoaded('variants', function () {
                return $this->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'product_id' => $variant->product_id,
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                        'quantity' => $variant->quantity,
                        'image' => !empty($variant->image) ? url($variant->image) : null,
                        'current_price' => $variant->current_price,
                        'stock_status' => $variant->stock_status,
                        'attributes' => $variant->attributes->map(function ($attribute) use ($variant) {
                            return [
                                'id' => $attribute->id,
                                'variant_id' => $variant->id,
                                'attribute_id' => $attribute->attribute_id,
                                'attribute_value_id' => $attribute->attribute_value_id,
                                'attribute_name' => $attribute->attribute->name,
                                'attribute_value' => $attribute->attributeValue->value,
                                'color_code' => $attribute->attributeValue->color_code,
                            ];
                        }),
                    ];
                });
            }),
            'options' => $this->whenLoaded('variants', function () {
                return $this->attributes->groupBy('attribute_id')->map(function ($attributeGroup) {
                    return [
                        'id' => $attributeGroup->first()->id,
                        'attribute_name' => $attributeGroup->first()->attribute->name,
                        'attribute_values' => $attributeGroup->map(function ($attribute) {
                            return [
                                'value' => $attribute->attributeValue->value,
                                'color_code' => $attribute->attributeValue->color_code,
                            ];
                        })->unique('value')->values()->all(),
                    ];
                })->values()->all();
            }),
            'images' => $this->whenLoaded('variants', collect($this->images)->map(fn ($image) => url($image))->all()),
            'brand' => null,
            'category' => $this->whenLoaded('category'),
            'stock_status' => $this->stock_status,
            'stock' => $this->current_stock,
            'sku' => $this->sku,
            'status' => $this->status->label(),
        ];
    }
}
