<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => '',
            'password' => '',
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'email' => 'O campo :attribute deve conter um endereço de e-mail válido.',
            'max' => 'O :attribute não pode ser maior que :max.',
            'min' => 'O :attribute deve conter no mínimo :min.',
        ];
    }
}
