<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $valueId = $this->route('attribute_value')?->id ?? null;

        return [
            'attribute_id' => ['required', 'integer', 'exists:attributes,id'],
            'value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('attribute_values', 'value')
                    ->ignore($valueId)
                    ->where('attribute_id', $this->input('attribute_id')),
            ],
            'color_code' => ['nullable', 'string', 'max:50'],
        ];
    }
}
