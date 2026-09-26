<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticSession extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'session_code',
        'student_name',
        'student_grade',
        'score',
        'total_questions',
        'correct_count',
        'mastery_level',
        'primary_misconception',
        'domain_scores',
        'ai_diagnosis_summary',
        'ai_remediation_plan',
        'status',
        'time_spent_seconds',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'total_questions' => 'integer',
            'correct_count' => 'integer',
            'time_spent_seconds' => 'integer',
            'domain_scores' => 'array',
        ];
    }

    public function studentResponses()
    {
        return $this->hasMany(StudentResponse::class);
    }

    public function adaptivePracticeQuestions()
    {
        return $this->hasMany(AdaptivePracticeQuestion::class);
    }
}
