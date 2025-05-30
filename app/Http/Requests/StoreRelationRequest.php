<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRelationRequest extends FormRequest
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
           'relations_master_id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'page_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_file_uploaded' => 'required|in:Y,N',
            'fileupload_count' => 'nullable|integer',
            'is_disabled' => 'required|boolean',
        ];
    }
}
