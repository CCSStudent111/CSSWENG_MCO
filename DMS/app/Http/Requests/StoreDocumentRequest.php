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
            'name' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'document_type_id' => 'nullable|exists:document_types,id', // Allow null
            'issued_at' => 'required|date',
            'pages' => 'array',
            'pages.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
            'hospital_ids' => 'array',
            'hospital_ids.*' => 'exists:hospitals,id',
            'employee_ids' => 'array',
            'employee_ids.*' => 'exists:users,id',
        ];
    }
}
