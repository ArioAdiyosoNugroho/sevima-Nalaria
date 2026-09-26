<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_code',
        'student_name',
        'student_grade',
        'score',
        'total_questions',
        'correct_count',
        'mastery_level',
        'primary_misconception',
        'ai_diagnosis_summary',
        'ai_remediation_plan',
        'domain_scores',
        'time_spent_seconds',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'domain_scores' => 'array',
            'score' => 'integer',
            'total_questions' => 'integer',
            'correct_count' => 'integer',
            'time_spent_seconds' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'session_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'session_id');
    }

    /**
     * Menghitung Skor Penguasaan Numerasi Teragregasi (0-100)
     * Menggabungkan akurasi jawaban di seluruh sesi dan tingkat penyelesaian latihan adaptif
     */
    public static function calculateUserMasteryScore(int $userId): array
    {
        $sessions = self::where('user_id', $userId)
            ->where('status', 'completed')
            ->with(['answers', 'recommendations'])
            ->get();

        if ($sessions->isEmpty()) {
            return [
                'score' => 0,
                'mastery_score' => 0,
                'level' => 'Belum Ada Data Asesmen',
                'total_sessions' => 0,
                'total_answers' => 0,
                'correct_answers' => 0,
                'remediations_completed' => 0,
                'avg_session_score' => 0,
                'average_diagnostic_score' => 0,
                'remediation_completion_rate' => 0,
            ];
        }

        $totalAnswers = 0;
        $correctAnswers = 0;
        $totalSessions = $sessions->count();
        $sumSessionScores = $sessions->sum('score');
        $avgSessionScore = $totalSessions > 0 ? ($sumSessionScores / $totalSessions) : 0;

        $totalRecommendations = 0;
        $solvedRecommendations = 0;

        foreach ($sessions as $sess) {
            $totalAnswers += $sess->answers->count();
            $correctAnswers += $sess->answers->where('is_correct', true)->count();
            $totalRecommendations += $sess->recommendations->count();
            $solvedRecommendations += $sess->recommendations->where('is_solved', true)->count();
        }

        $remediationRate = $totalRecommendations > 0
            ? (int) round(($solvedRecommendations / $totalRecommendations) * 100)
            : 0;

        // Bobot: 70% Skor Diagnostik Rata-rata + 30% Remediasi Berhasil
        $remediationBonus = ($remediationRate / 100) * 30;

        $masteryScore = (int) round(($avgSessionScore * 0.70) + $remediationBonus);
        $masteryScore = min(100, max(0, $masteryScore));

        $level = match (true) {
            $masteryScore >= 80 => 'Mahir (Level 4/5 PISA)',
            $masteryScore >= 60 => 'Cakap (Level 3 PISA)',
            $masteryScore >= 40 => 'Dasar (Level 2 PISA)',
            default => 'Perlu Intervensi Khusus (Level 1 PISA)',
        };

        return [
            'score' => $masteryScore,
            'mastery_score' => $masteryScore,
            'level' => $level,
            'total_sessions' => $totalSessions,
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'remediations_completed' => $solvedRecommendations,
            'avg_session_score' => (int) round($avgSessionScore),
            'average_diagnostic_score' => (int) round($avgSessionScore),
            'remediation_completion_rate' => $remediationRate,
        ];
    }
}
