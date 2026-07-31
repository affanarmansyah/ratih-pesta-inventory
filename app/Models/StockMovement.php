<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['item_id', 'event_id', 'type', 'quantity', 'condition', 'movement_date', 'notes'];

    protected $casts = [
        'movement_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
