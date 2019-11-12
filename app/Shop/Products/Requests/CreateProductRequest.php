<?php

namespace App\Shop\Products\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateProductRequest extends BaseFormRequest
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
            'name' => ['required', 'unique:products'],
            'quantity' => ['required', 'numeric'],
            'price' => ['required'],
            'cover' => ['required', 'file', 'image:png,jpeg,jpg,gif']
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
            'cover.required' => 'Imagem principal é obrigatório',
            'cover.file' => 'Imagem principal com formato com_invoke(alido. Formatos suportados: png,jpeg,jpg,gif',
            'name.required'  => 'Nome é obrigatório',
            'name.unique'  => 'Um produto com este nome já existe',
            'quantity.required'  => 'Quantidade  é obrigatório',
            'quantity.numeric'  => 'Quantidade precisa ser um valor numerico',
            'price.required'  => 'Preço é obrigatório',
        ];
    }
}
