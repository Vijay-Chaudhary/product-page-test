<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if($this->method() == 'PUT') {
            return [
                'name'        => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                // 'slug'        => ['required', 'string','unique:products,slug', 'max:255'],
                'price'       => ['required', 'numeric'],
                'active'      => ['required', 'boolean'],
                'images.*'    => ['required', 'file', 'mimes:jpeg,png,svg,webp,gif,jpg', 'max:2048'],
                'discount'    => ['nullable', 'numeric'],
                'type'        => ['nullable', 'in:amount,percent'],
            ];
        } else {
            return [
                'name'        => ['sometimes', 'required', 'string', 'max:255'],
                'description' => ['sometimes', 'required', 'string'],
                // 'slug'        => ['sometimes', 'required', 'string','unique:products,slug', 'max:255'],
                'price'       => ['sometimes', 'required', 'numeric'],
                'active'      => ['sometimes', 'required', 'boolean'],
                'images.*'    => ['sometimes', 'required', 'file', 'mimes:jpeg,png,svg,webp,gif,jpg', 'max:2048'],
                'discount'    => ['sometimes', 'nullable', 'numeric'],
                'type'        => ['sometimes', 'nullable', 'in:amount,percent'],
            ];
        }
    }
}
