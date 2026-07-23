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
        Schema::table('destiny_matches', function (Blueprint $table) {
            $table->string('role')->nullable()->after('match_mode'); // speak, listen
            $table->integer('duration')->default(10)->after('role'); // 10, 20, 30
            $table->integer('mood_score')->nullable()->after('duration'); // 1 to 100
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destiny_matches', function (Blueprint $table) {
            $table->dropColumn(['role', 'duration', 'mood_score']);
        });
    }
};
