<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'gender' => ['required', 'in:male,female,unisex'],
            'color' => ['nullable', 'string', 'max:50'],
            'material' => ['nullable', 'string', 'max:100'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required'],
        ];
    }
}
