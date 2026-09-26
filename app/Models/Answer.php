<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'diagnostic_question_id',
        'question_text',
        'student_answer',
        'student_reasoning',
        'is_correct',
        'misconception_category',
        'misconception_detail',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(AssessmentSession::class, 'session_id');
    }

    public function diagnosticQuestion(): BelongsTo
    {
        return $this->belongsTo(DiagnosticQuestion::class, 'diagnostic_question_id');
    }
}
