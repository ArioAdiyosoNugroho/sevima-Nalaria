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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('assessment_sessions')->cascadeOnDelete();
            $table->string('target_misconception', 150)->nullable();
            $table->string('domain', 50)->nullable();
            $table->string('title', 150)->nullable();
            $table->text('context_scenario')->nullable();
            $table->text('generated_question');
            $table->json('options')->nullable();
            $table->string('correct_answer', 10);
            $table->text('explanation');
            $table->text('scaffolding_hint')->nullable();
            $table->string('difficulty_level', 50)->default('Sedang');
            $table->boolean('is_solved')->default(false);
            $table->string('student_answer', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
