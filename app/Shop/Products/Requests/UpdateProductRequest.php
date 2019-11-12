<?php

namespace App\Shop\Products\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku' => ['required'],
            'name' => ['required', Rule::unique('products')->ignore($this->segment(3))],
            'quantity' => ['required', 'integer'],
            'price' => ['required']
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sku.required' => 'SKU é obrigatório',
            'name.required'  => 'Nome é obrigatório',
            'name.unique'  => 'Um produto com este nome já existe',
            'quantity.required'  => 'Quantidade  é obrigatório',
            'quantity.numeric'  => 'Quantidade precisa ser um valor numerico',
            'price.required'  => 'Preço é obrigatório',
        ];
    }
}
