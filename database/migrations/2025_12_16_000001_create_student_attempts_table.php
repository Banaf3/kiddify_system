<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('section_id');
            $table->string('selected_answer', 1); // A, B, or C
            $table->boolean('is_correct')->default(false);
            $table->integer('marks_obtained')->default(0);
            $table->integer('attempt_number')->default(1);
            $table->timestamps();

            $table->foreign('student_id')->references('studentID')->on('students')->onDelete('cascade');
            $table->foreign('assessment_id')->references('AssessmentID')->on('assessments')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            
            // Index for quick lookups
            $table->index(['student_id', 'section_id', 'attempt_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attempts');
    }
};
