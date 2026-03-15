<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'is_draft' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'target_year_levels' => 'array',
    ];

    public function attachments()
    {
        return $this->hasMany(AnnouncementAttachment::class);
    }
}
