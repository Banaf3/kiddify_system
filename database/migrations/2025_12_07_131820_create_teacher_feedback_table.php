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
    Schema::create('teacher_feedback', function (Blueprint $table) {
        $table->id('feedback_id');
        $table->unsignedBigInteger('StudentID');
        $table->unsignedBigInteger('CourseID');
        $table->unsignedBigInteger('teachersID');
        $table->string('comments', 100);
        $table->timestamps();

        $table->foreign('StudentID')->references('studentID')->on('students')->onDelete('cascade');
        $table->foreign('CourseID')->references('CourseID')->on('courses')->onDelete('cascade');
        $table->foreign('teachersID')->references('teachersID')->on('teachers')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('teacher_feedback');
}

};
