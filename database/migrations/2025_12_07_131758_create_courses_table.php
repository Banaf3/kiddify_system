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
    Schema::create('courses', function (Blueprint $table) {
        $table->id('CourseID');
        $table->unsignedBigInteger('teachersID');
        $table->string('Title', 30);
        $table->string('description', 50);
        $table->string('Start_time', 20);
        $table->string('end_time', 20);
       $table->text('days');
        $table->string('T_name')->nullable();
        $table->integer('maxStudent')->default(20);
        $table->unsignedBigInteger('StudentID');
        $table->timestamps();

        $table->foreign('teachersID')->references('teachersID')->on('teachers')->onDelete('cascade');
        $table->foreign('StudentID')->references('studentID')->on('students')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('courses');
}

};
