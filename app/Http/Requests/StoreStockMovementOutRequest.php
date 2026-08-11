<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'event_id' => ['required', 'exists:events,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'movement_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->filled('item_id') || ! $this->filled('quantity')) {
                return;
            }

            $item = Item::find($this->input('item_id'));
            if (! $item) {
                return;
            }

            if ($this->input('quantity') > $item->stock_available) {
                $validator->errors()->add('quantity', "Stok tersedia cuma {$item->stock_available}, gak cukup untuk {$this->input('quantity')}");
            }
        });
    }
}
