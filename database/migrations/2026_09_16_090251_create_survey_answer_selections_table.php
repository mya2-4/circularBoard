<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_answer_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_answer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_question_option_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answer_selections');
    }
};
