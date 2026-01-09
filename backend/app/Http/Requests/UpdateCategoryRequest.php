<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Category;

class UpdateCategoryRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($this->route('category')?->id)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['nullable', Rule::in(StatusEnum::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $name = (string) $this->input('name', '');
        $slugBase = Str::slug($name);
        $slug = $slugBase;
        $counter = 1;
        $categoryId = $this->route('category')?->id;

        while ($slug && Category::where('slug', $slug)->when($categoryId, function ($query) use ($categoryId) {
            $query->where('id', '!=', $categoryId);
        })->exists()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        $this->merge([
            'slug' => $slug,
        ]);
    }
}
