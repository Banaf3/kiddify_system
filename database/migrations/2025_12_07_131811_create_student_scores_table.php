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
    Schema::create('student_scores', function (Blueprint $table) {
        $table->id('score_id');
        $table->unsignedBigInteger('StudentID');
        $table->unsignedBigInteger('AssessmentID');
        $table->unsignedBigInteger('CourseID');
        $table->integer('marks_obtained');
        $table->integer('total_marks');
        $table->string('grading_status', 10);
        $table->timestamps();

        $table->foreign('StudentID')->references('studentID')->on('students')->onDelete('cascade');
        $table->foreign('AssessmentID')->references('AssessmentID')->on('assessments')->onDelete('cascade');
        $table->foreign('CourseID')->references('CourseID')->on('courses')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('student_scores');
}

};
