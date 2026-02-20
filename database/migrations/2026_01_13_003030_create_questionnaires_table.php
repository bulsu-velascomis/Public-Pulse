<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up()
    // {
    //     Schema::create("questionnaires", function (Blueprint $table) {
    //         $table->id();
    //         $table
    //             ->foreignId("section_header_id")
    //             ->constrained("section_headers")
    //             ->onDelete("cascade");
    //         $table
    //             ->foreignId("answer_id")
    //             ->constrained("answers")
    //             ->onDelete("cascade");
    //         $table->json("questions");
    //         $table->unsignedInteger("display_order")->default(1);
    //         $table->timestamps();
    //         $table->softDeletes();
    //     });
    // }

    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_header_id')->constrained('section_headers')->onDelete('cascade');
            $table->json('questions');
            $table->enum('type', ['Text', 'Textarea', 'Multiple Choice', 'Checkbox','Optional'])->default('text');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("questionnaires");
    }
};
