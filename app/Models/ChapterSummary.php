<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterSummary extends Model
{
    protected $fillable = [
        'chapter_id',
        'summary',
        'key_points'
    ];

    protected $casts = [
        'key_points' => 'array'
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
