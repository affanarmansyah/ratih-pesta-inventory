<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpnameRecord extends Model
{
    protected $fillable = ['user_id', 'item_id', 'session_date', 'system_qty', 'physical_qty', 'difference', 'notes'];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
