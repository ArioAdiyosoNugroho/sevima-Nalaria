<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResponse extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'diagnostic_session_id',
        'diagnostic_question_id',
        'selected_option',
        'is_correct',
        'student_reasoning',
        'detected_misconception',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function diagnosticSession()
    {
        return $this->belongsTo(DiagnosticSession::class);
    }

    public function diagnosticQuestion()
    {
        return $this->belongsTo(DiagnosticQuestion::class);
    }
}
