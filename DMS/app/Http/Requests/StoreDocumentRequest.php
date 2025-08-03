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
            'pages.*' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    $sizeInMB = $value->getSize() / 1024 / 1024; // convert bytes to MB

                    if (in_array($extension, ['png', 'jpeg', 'jpg']) && $sizeInMB > 15) {
                        $fail('Image files must not exceed 15MB.');
                    } elseif ($extension === 'pdf' && $sizeInMB > 150) {
                        $fail('PDF files must not exceed 150MB.');
                    } elseif (in_array($extension, ['docx', 'pptx', 'xlsx']) && $sizeInMB > 2048) {
                        $fail('Document files must not exceed 2GB.');
                    } elseif (!in_array($extension, ['png', 'jpeg', 'jpg', 'pdf', 'docx', 'pptx', 'xlsx'])) {
                        $fail('Invalid file type: ' . $extension);
                    }
                }
            ],
            'issued_at' => ['required', 'date'],
            'target_type' => ['required', 'in:General,Client'],
            'user_id' => ['nullable', 'exists:users,id'], // or 'clients,id' depending on target_type
        ];
    }
}
