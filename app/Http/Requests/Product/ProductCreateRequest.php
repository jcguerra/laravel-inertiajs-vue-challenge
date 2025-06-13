<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be text',
            'name.max' => 'Name cannot be longer than 255 characters',
            'description.string' => 'Description must be text',
            'description.max' => 'Description cannot be longer than 255 characters',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be greater than or equal to 0',
            'stock.integer' => 'Stock must be an integer',
            'stock.min' => 'Stock must be greater than or equal to 0',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'The image must be of type: jpeg, png, jpg, gif, webp',
            'image.max' => 'The image cannot be larger than 2MB',
        ];
    }
}
