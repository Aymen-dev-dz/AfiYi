<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->string('currency')->default('EUR')->after('price');
            $table->boolean('is_first_session')->default(false)->after('currency');
            $table->boolean('is_free')->default(false)->after('is_first_session');
            $table->string('daily_room_name')->nullable()->after('meet_link');
            $table->text('daily_room_url')->nullable()->after('daily_room_name');
            $table->text('patient_token')->nullable()->after('daily_room_url');
            $table->text('therapist_token')->nullable()->after('patient_token');
            $table->timestamp('room_created_at')->nullable()->after('therapist_token');
            $table->timestamp('room_expires_at')->nullable()->after('room_created_at');
            $table->string('payment_intent_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_intent_id');
            $table->timestamp('started_at')->nullable()->after('paid_at');
            $table->timestamp('ended_at')->nullable()->after('started_at');
            $table->integer('actual_duration_minutes')->nullable()->after('ended_at');
            $table->text('cancellation_reason')->nullable()->after('actual_duration_minutes');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'currency', 'is_first_session', 'is_free', 'daily_room_name',
                'daily_room_url', 'patient_token', 'therapist_token',
                'room_created_at', 'room_expires_at', 'payment_intent_id',
                'paid_at', 'started_at', 'ended_at', 'actual_duration_minutes',
                'cancellation_reason', 'cancelled_at'
            ]);
        });
    }
};
