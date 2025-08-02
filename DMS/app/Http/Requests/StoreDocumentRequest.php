<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'summary' => ['required', 'string'],
            'document_type_id' => 'nullable|exists:document_types,id', // Allow null
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'pages' => ['required', 'array'],
            'pages.*' => ['file'], 
            'issued_at' => ['required', 'date'],
            'target_type' => ['required', 'in:General,Employee,Client'],
            'user_id' => ['nullable', 'exists:users,id'], // or 'clients,id' depending on target_type
        ];
    }
}
