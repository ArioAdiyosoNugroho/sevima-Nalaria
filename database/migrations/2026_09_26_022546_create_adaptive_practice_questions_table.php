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
        Schema::create('adaptive_practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostic_session_id')->constrained()->cascadeOnDelete();
            $table->string('target_misconception');
            $table->string('domain', 50);
            $table->string('title');
            $table->text('context_scenario');
            $table->text('question_text');
            $table->json('options');
            $table->string('correct_answer', 10);
            $table->text('scaffolding_hint')->nullable();
            $table->text('conceptual_explanation');
            $table->string('difficulty', 30)->default('Sedang');
            $table->string('student_answer', 10)->nullable();
            $table->boolean('is_solved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adaptive_practice_questions');
    }
};
