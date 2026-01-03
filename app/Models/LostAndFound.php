<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostAndFound extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date_reported' => 'datetime',
        'resolved_at' => 'datetime',
        'is_anonymous' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function matchedItem()
    {
        return $this->belongsTo(LostAndFound::class, 'matched_item_id');
    }
}
