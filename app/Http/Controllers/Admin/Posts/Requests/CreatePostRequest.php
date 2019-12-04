<?php

namespace App\Shop\Posts\Requests;

use App\Shop\Base\BaseFormRequest;

class CreatePostRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:posts']
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
            'name.unique'  => 'Uma postagem com este nome já existe',
        ];
    }
}

