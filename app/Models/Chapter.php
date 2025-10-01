<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chapter extends Model
{
    protected $fillable = [
        'story_id',
        'title',
        'order',
        'content'
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function summary(): HasOne
    {
        return $this->hasOne(ChapterSummary::class);
    }
}
