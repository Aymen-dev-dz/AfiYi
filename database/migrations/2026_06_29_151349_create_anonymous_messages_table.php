<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anonymous_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anonymous_room_id')->constrained('anonymous_rooms')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            
            // Text is stored encrypted at the model level for privacy, but accessible to AI mod
            $table->text('message');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anonymous_messages');
    }
};
