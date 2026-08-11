<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreStockMovementInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'event_id' => ['nullable', 'exists:events,id'],
            'qty_baik' => ['nullable', 'integer', 'min:0'],
            'qty_rusak' => ['nullable', 'integer', 'min:0'],
            'qty_hilang' => ['nullable', 'integer', 'min:0'],
            'movement_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $qtyBaik = $this->input('qty_baik', 0) ?? 0;
            $qtyRusak = $this->input('qty_rusak', 0) ?? 0;
            $qtyHilang = $this->input('qty_hilang', 0) ?? 0;
            $totalQty = $qtyBaik + $qtyRusak + $qtyHilang;

            if ($totalQty === 0) {
                $validator->errors()->add('qty_baik', 'Isi minimal salah satu jumlah kondisi barang');
                return;
            }

            if (! empty($this->input('event_id'))) {
                $outstanding = DB::table('stock_movements')
                    ->where('event_id', $this->input('event_id'))
                    ->where('item_id', $this->input('item_id'))
                    ->whereNull('voided_at')
                    ->selectRaw("SUM(CASE WHEN type='keluar' THEN quantity ELSE -quantity END) as outstanding")
                    ->value('outstanding') ?? 0;

                if ($totalQty > $outstanding) {
                    $validator->errors()->add('qty_baik', "Total yang dimasukkan ({$totalQty}) melebihi sisa barang yang masih di luar untuk event ini ({$outstanding})");
                }
            }
        });
    }
}
