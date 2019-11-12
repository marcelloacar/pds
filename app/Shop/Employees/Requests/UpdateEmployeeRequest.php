<?php

namespace App\Shop\Admins\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('employees', 'email')->ignore($this->segment(3))]
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
        'email.unique'  => 'Já existe um funcionário cadastrado com esse e-mail',
        ];
    }
}
