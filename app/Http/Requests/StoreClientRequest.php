<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize():bool
    {
        //return true;
        return Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ];
    }


    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => true,
            'message' => "Erreur de validation",
            'errorsList' => $validator->errors(),
        ]));
    }


    public function messages() {
        return [
            'name.required' => "Le nom de l'utilisateur est requis",
            'email.required' => "L'email de l'utilisateur est requis",
            'email.email' => "L'email est invalide",
            'password.required' => "Le mot de passe de l'utilisateur est requis",
            'password.min' => "Le mot de passe doit comporter minimum 6 caractères"
        ];
       
    }

}
