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
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_name' => 'required|string|max:255',
            'ref_id' => 'required|integer',
            'uploaded_file_desc' => 'nullable|string',
            'uploaded_file' => 'nullable|file|max:10240', // 10MB max
            'url' => 'nullable|url',
            'publication' => 'nullable|string|max:255',
            'is_disabled' => 'boolean',
            // Note: precedence is not included here as it's auto-assigned
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'table_name.required' => 'Table name is required.',
            'ref_id.required' => 'Reference ID is required.',
            'ref_id.integer' => 'Reference ID must be a number.',
            'uploaded_file.max' => 'File size must not exceed 10MB.',
            'url.url' => 'Please enter a valid URL.',
        ];
    }
}