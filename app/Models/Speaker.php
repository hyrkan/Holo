<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use \App\Traits\HasImage;

    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        return $this->getImageUrl('image');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
