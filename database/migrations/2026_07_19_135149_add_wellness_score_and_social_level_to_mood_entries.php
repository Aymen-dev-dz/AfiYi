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
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->unsignedInteger('wellness_score')->nullable()->after('energy_level');
            $table->unsignedTinyInteger('social_level')->nullable()->after('wellness_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mood_entries', function (Blueprint $table) {
            $table->dropColumn(['wellness_score', 'social_level']);
        });
    }
};
