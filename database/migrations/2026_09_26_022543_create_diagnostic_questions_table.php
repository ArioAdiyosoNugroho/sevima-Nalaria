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
        Schema::create('diagnostic_questions', function (Blueprint $table) {
            $table->id();
            $table->string('domain'); // aljabar, geometri, aritmatika_sosial, data_ketidakpastian
            $table->string('title');
            $table->text('context_scenario');
            $table->text('question_text');
            $table->json('options');
            $table->string('correct_answer', 10);
            $table->json('misconception_map')->nullable();
            $table->text('conceptual_explanation');
            $table->unsignedInteger('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_questions');
    }
};
