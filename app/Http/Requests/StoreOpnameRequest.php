<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpnameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_date' => ['required', 'date'],
            'physical_qty' => ['required', 'array'],
            'physical_qty.*' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'array'],
        ];
    }
}
