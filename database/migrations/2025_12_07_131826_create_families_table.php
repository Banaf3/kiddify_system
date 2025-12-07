<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
           $table->id('FamilyID');
        $table->unsignedBigInteger('parent_id');
        $table->unsignedBigInteger('StudentID');
        $table->string('relationship_type', 15);
        $table->timestamps();

        $table->foreign('parent_id')->references('parent_id')->on('parent_models')->onDelete('cascade');
        $table->foreign('StudentID')->references('studentID')->on('students')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
