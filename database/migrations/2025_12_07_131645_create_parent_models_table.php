<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_models', function (Blueprint $table) {
            $table->id('parent_id');
            $table->string('occupation', 20);
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

    public function down(): void
    {
        Schema::dropIfExists('parent_models');
    }
};
