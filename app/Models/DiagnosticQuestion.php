<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticQuestion extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'domain',
        'title',
        'context_scenario',
        'question_text',
        'options',
        'correct_answer',
        'misconception_map',
        'conceptual_explanation',
        'order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'misconception_map' => 'array',
            'order' => 'integer',
        ];
    }

    public function studentResponses()
    {
        return $this->hasMany(StudentResponse::class);
    }
}
