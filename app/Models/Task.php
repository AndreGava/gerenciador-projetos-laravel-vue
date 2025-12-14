<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'completed',
        'project_id',
        'difficulty',
    ];

    const DIFFICULTY_POINTS = [
        'baixa' => 1,
        'média' => 4,
        'alta' => 12,
    ];

    public function getEffortPointsAttribute(): int
    {
        return self::DIFFICULTY_POINTS[$this->difficulty] ?? 0;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
