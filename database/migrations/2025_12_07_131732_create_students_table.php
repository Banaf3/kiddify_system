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
    Schema::create('students', function (Blueprint $table) {
        $table->id('studentID');
        $table->unsignedBigInteger('UserID');
        $table->string('school_branch', 20);
        $table->string('class_name', 20);
        $table->string('account_status', 20);
       // Use Laravel standard foreign key referencing users.id
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('students');
}

};
