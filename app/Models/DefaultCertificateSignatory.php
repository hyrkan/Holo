<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultCertificateSignatory extends Model
{
    use \App\Traits\HasImage;

    protected $guarded = [];

    public function getSignatureImageUrlAttribute()
    {
        return $this->getImageUrl('signature_image', 'signatures');
    }

    public function certificate()
    {
        return $this->belongsTo(DefaultCertificate::class, 'default_certificate_id');
    }
}
