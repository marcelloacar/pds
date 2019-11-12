<?php

namespace App\Shop\Admins\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:employees'],
            'password' => ['required', 'min:8'],
            'role' => ['required']
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
        'password.required'  => 'Senha é obrigatório',
        'password.min'  => 'Senha precisa ter pelo menos 8 caracteres',
        'role.required'  => 'Atribuições é obrigatório',
        'email.unique'  => 'Já existe um funcionário cadastrado com esse e-mail',
        ];
    }
}
