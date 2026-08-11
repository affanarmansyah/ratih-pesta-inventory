<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,berlangsung,selesai'],
        ];
    }
}
