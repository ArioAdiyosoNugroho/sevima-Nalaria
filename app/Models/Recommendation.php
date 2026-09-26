<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'target_misconception',
        'domain',
        'title',
        'context_scenario',
        'generated_question',
        'options',
        'correct_answer',
        'explanation',
        'scaffolding_hint',
        'difficulty_level',
        'is_solved',
        'student_answer',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_solved' => 'boolean',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(AssessmentSession::class, 'session_id');
    }
}
