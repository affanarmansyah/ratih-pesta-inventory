<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['customer_name', 'event_date', 'location', 'status'];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
