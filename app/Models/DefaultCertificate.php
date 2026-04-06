<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultCertificate extends Model
{
    use \App\Traits\HasImage;

    protected $guarded = [];

    public function getBackgroundImageUrlAttribute()
    {
        return $this->getImageUrl('background_image');
    }

    public function signatories()
    {
        return $this->hasMany(DefaultCertificateSignatory::class)->orderBy('order');
    }

    /**
     * Scope to get the currently selected default certificate.
     */
    public function scopeSelected($query)
    {
        return $query->where('is_selected', true);
    }
}
