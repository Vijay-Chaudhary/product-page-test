<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
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

        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'slug'     => ['required', 'string','unique:products,slug', 'max:255'],
            'price'       => ['required', 'numeric'],
            'active'      => ['required', 'boolean'],
            'images.*'    => ['required', 'file', 'mimes:jpeg,png,svg,webp,gif,jpg', 'max:2048'],
            'discount'    => ['nullable', 'numeric'],
            'type'        => ['nullable', 'in:amount,percent'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
