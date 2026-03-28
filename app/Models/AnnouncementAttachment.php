<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementAttachment extends Model
{
    protected $guarded = [];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function getFileUrlAttribute()
    {
        return \App\Helpers\ImageStorage::url($this->file_path);
    }
}
