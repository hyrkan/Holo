<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateSignatory extends Model
{
    protected $guarded = [];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    use \App\Traits\HasImage;

    public function getSignatureImageUrlAttribute()
    {
        return $this->getImageUrl('signature_image');
    }
}
