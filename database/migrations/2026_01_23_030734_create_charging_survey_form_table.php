<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('charging_survey_form', function (Blueprint $table) {
            $table->foreignId('charging_id')->constrained('chargings')->onDelete('cascade');
            $table->foreignId('survey_form_id')->constrained('survey_forms')->onDelete('cascade');
            $table->primary(['charging_id', 'survey_form_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('charging_survey_form');
    }
};
