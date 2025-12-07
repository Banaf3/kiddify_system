<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('assessments', function (Blueprint $table) {
        $table->id('AssessmentID');
        $table->string('question', 100);
        $table->integer('grade');
        $table->string('answer', 50);
        $table->unsignedBigInteger('CourseID');
        $table->timestamps();

        $table->foreign('CourseID')->references('CourseID')->on('courses')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('assessments');
}

};
