<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateCustomerRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:customers'],
            'password' => ['required', 'min:8']
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
        'email.required'  => 'E-mail é obrigatório',
        'email.unique'  => 'Já existe um cliente cadastrado com esse e-mail',
        'password.required'  => 'Senha é obrigatório',
        'password.min'  => 'Senha precisa ter pelo menos 8 caracteres',
        ];
    }
}
