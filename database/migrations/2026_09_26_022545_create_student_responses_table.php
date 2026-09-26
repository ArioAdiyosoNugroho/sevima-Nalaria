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
        Schema::create('student_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diagnostic_question_id')->constrained()->cascadeOnDelete();
            $table->string('selected_option', 10);
            $table->boolean('is_correct')->default(false);
            $table->text('student_reasoning')->nullable();
            $table->string('detected_misconception')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_responses');
    }
};
