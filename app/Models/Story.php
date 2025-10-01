<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'outline',
        'target_chapters',
        'characters',
        'existing_story',
        'end_goal',
        'generated_plot'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function chapterSummaries(): HasMany
    {
        return $this->hasManyThrough(ChapterSummary::class, Chapter::class);
    }
}
