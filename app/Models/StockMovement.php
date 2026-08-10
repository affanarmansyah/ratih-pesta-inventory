<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'event_id',
        'type',
        'quantity',
        'condition',
        'movement_date',
        'notes',
        'voided_at',
        'voided_by',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'voided_at' => 'datetime',
    ];

    public function voidedBy()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
