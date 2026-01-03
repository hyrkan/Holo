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
}
