<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdaptivePracticeQuestion extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'diagnostic_session_id',
        'target_misconception',
        'domain',
        'title',
        'context_scenario',
        'question_text',
        'options',
        'correct_answer',
        'scaffolding_hint',
        'conceptual_explanation',
        'difficulty',
        'student_answer',
        'is_solved',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_solved' => 'boolean',
        ];
    }

    public function diagnosticSession()
    {
        return $this->belongsTo(DiagnosticSession::class);
    }
}
