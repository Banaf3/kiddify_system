<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->boolean('review_enabled')->default(false)->after('attempt_limit');
            $table->boolean('grade_visible')->default(false)->after('review_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['review_enabled', 'grade_visible']);
        });
    }
};
