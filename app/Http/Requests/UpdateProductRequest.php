<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $productId = request()->route('product')->id;
        return [
            'name' => ['nullable', Rule::unique('products', 'name')->ignore($productId), 'string', 'max:255'],
            'sku' => ['nullable', Rule::unique('products', 'sku')->ignore( $productId), 'string'],
            'quantity' => ['nullable', 'numeric', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:1'],
            'description' => ['nullable', 'string']
        ];
    }
}
