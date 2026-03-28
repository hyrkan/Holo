<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostAndFound extends Model
{
    use \App\Traits\HasImage;

    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        return $this->getImageUrl('image_path');
    }

    public function getHandoverImageUrlAttribute()
    {
        return $this->getImageUrl('handover_image_path');
    }

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
