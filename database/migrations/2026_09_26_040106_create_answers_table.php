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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('assessment_sessions')->cascadeOnDelete();
            $table->foreignId('diagnostic_question_id')->nullable()->constrained('diagnostic_questions')->nullOnDelete();
            $table->text('question_text');
            $table->string('student_answer', 10);
            $table->text('student_reasoning')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->string('misconception_category', 100)->nullable();
            $table->text('misconception_detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
