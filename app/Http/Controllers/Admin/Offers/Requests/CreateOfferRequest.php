<?php

namespace App\Shop\Offers\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateOfferRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:offers']
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
            'name.required'  => 'Nome é obrigatório',
            'name.unique'  => 'Um anúncio com este nome já existe',
        ];
    }
}

