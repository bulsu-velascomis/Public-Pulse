<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->integer('question_no');
            $table->text('answer');
            $table->softDeletes();
            $table->timestamps();
            
            $table->unique(['questionnaire_id', 'question_no']);
        });
    }

    public function down()
    {
        Schema::dropIfExists("answers");
    }
};
