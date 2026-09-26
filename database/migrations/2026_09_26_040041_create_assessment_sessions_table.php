<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('session_code', 32)->unique();
            $table->string('student_name', 100);
            $table->string('student_grade', 50)->nullable();
            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(0);
            $table->integer('correct_count')->default(0);
            $table->string('mastery_level', 50)->nullable();
            $table->string('primary_misconception', 150)->nullable();
            $table->text('ai_diagnosis_summary')->nullable();
            $table->text('ai_remediation_plan')->nullable();
            $table->json('domain_scores')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            $table->string('status', 20)->default('in_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_sessions');
    }
};
