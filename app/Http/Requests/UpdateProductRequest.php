<?php

namespace App\Http\Requests;

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
        return $this->user()->tokenCan('product:update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required_without_all:price,quantity|min:6|max:255',
            'price' => 'required_without_all:name,quantity|numeric|between:0.01,99999999.99',
            'quantity' => 'required_without_all:name,price|integer|min:0',
        ];
    }
}
