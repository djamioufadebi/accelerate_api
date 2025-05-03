<?php

namespace App\Http\Requests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'client_id' => 'required|exists:clients,id',
            'issue_date' => 'required|date|before_or_equal:today',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.amount' => 'required|numeric|min:0',
            'status' => '',
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


    public function messages()
    {
        return [
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client spécifié n’existe pas.',
            'issue_date.before_or_equal' => 'La date d’émission doit être aujourd’hui ou antérieure.',
            'due_date.after_or_equal' => 'La date d’échéance doit être postérieure ou égale à la date d’émission.',
            'lines.min' => 'Au moins une ligne de facture est requise.',
        ];
    }

    
}
