<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:500', 'unique:products,slug'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', Rule::in(['fixed', 'percent'])],
            'description' => ['nullable', 'string'],
            'current_stock' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(StatusEnum::values())],
            'variations' => ['nullable', 'array'],
            'variations.*.sku' => ['nullable', 'string', 'max:100'],
            'variations.*.price' => ['nullable', 'numeric', 'min:0'],
            'variations.*.quantity' => ['nullable', 'integer', 'min:0'],
            'variations.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'variations.*.attributes' => ['nullable', 'array'],
            'variations.*.attributes.*.attribute_id' => ['required_with:variations.*.attributes', 'integer', 'exists:attributes,id'],
            'variations.*.attributes.*.attribute_value_id' => ['required_with:variations.*.attributes.*.attribute_id', 'integer', 'exists:attribute_values,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $name = (string) $this->input('name', '');
        $slugBase = Str::slug($name);
        $slug = $slugBase;
        $counter = 1;

        while ($slug && Product::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        $this->merge([
            'slug' => $slug,
        ]);
    }
}
