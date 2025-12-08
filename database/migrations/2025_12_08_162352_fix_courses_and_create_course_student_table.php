<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    // Modify courses table
    Schema::table('courses', function (Blueprint $table) {
        if (Schema::hasColumn('courses', 'StudentID')) {
            $table->dropForeign(['StudentID']);
            $table->dropColumn('StudentID'); // remove single student
        }

        if (!Schema::hasColumn('courses', 'days')) {
            $table->json('days')->nullable(); // only add if it doesn't exist
        }

        $table->time('Start_time')->change();
        $table->time('end_time')->change();
    });

    // Create pivot table for course-student many-to-many
    if (!Schema::hasTable('course_student')) {
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();

            $table->foreign('course_id')->references('CourseID')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('studentID')->on('students')->onDelete('cascade');
        });
    }
}


    public function down()
    {
        Schema::dropIfExists('course_student');

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('StudentID');
            $table->string('Start_time', 20)->change();
            $table->string('end_time', 20)->change();
            $table->dropColumn('days');
        });
    }
};
