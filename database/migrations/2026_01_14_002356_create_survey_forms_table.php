<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create("survey_forms", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table
                ->foreignId("questionnaire_id")
                ->constrained("questionnaires")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("description");
            $table
                ->enum("status", ["active", "inactive", "draft", "archived"])
                ->default("draft");
            $table->string("attachment")->nullable();
            $table->dateTime("date_start")->nullable();
            $table->dateTime("date_end")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists("survey_forms");
    }
};
