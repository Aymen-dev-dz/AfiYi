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
        Schema::create('destiny_matches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_a_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_b_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('waiting'); // waiting, active, closed
            $table->string('match_mode')->default('random'); // random, mood, interest, language
            $table->string('topic')->nullable();
            $table->string('user_a_nickname')->nullable();
            $table->string('user_b_nickname')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destiny_matches');
    }
};
