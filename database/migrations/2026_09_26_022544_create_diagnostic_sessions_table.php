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
        Schema::create('diagnostic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_code', 32)->unique();
            $table->string('student_name');
            $table->string('student_grade')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->string('mastery_level', 50)->default('Perlu Intervensi');
            $table->string('primary_misconception')->nullable();
            $table->json('domain_scores')->nullable();
            $table->text('ai_diagnosis_summary')->nullable();
            $table->text('ai_remediation_plan')->nullable();
            $table->string('status', 30)->default('completed');
            $table->unsignedInteger('time_spent_seconds')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_sessions');
    }
};
