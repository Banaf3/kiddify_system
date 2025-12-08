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
    Schema::create('admin_approval', function (Blueprint $table) {
        $table->id('Approval_id');
        $table->unsignedBigInteger('UserID');
        $table->string('status', 10);
        $table->string('remarks', 20)->nullable();
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
    Schema::dropIfExists('admin_approval');
}

};
