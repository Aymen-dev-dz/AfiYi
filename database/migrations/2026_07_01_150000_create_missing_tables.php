<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('therapist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->json('languages')->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('therapist_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_profile_id')->constrained()->cascadeOnDelete();
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('currency')->default('EUR');
            $table->string('coupon_code')->nullable();
            $table->text('notes')->nullable();
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('product_variant_id')->nullable();
            $table->foreignId('seller_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('variant_label')->nullable();
            $table->string('sku')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->json('product_snapshot')->nullable();
            $table->string('fulfillment_status')->default('pending');
            $table->string('destiny_token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('therapist_profile_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->string('status')->default('scheduled');
            $table->string('type')->default('video');
            $table->string('reference')->unique();
            $table->string('meet_link')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('payment_status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('consultation_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('therapist_profile_id')->constrained()->cascadeOnDelete();
            $table->string('visibility')->default('private');
            $table->text('content_encrypted')->nullable();
            $table->string('encryption_iv')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_session_summary')->default(false);
            $table->text('note')->nullable(); // Left for fallback if needed
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('mood_score');
            $table->string('emotion')->nullable();
            $table->integer('stress_level')->nullable();
            $table->integer('sleep_quality')->nullable();
            $table->integer('energy_level')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_conversation_id')->constrained()->cascadeOnDelete();
            $table->string('sender'); // user or ai
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('mood_entries');
        Schema::dropIfExists('consultation_notes');
        Schema::dropIfExists('consultations');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('therapist_schedules');
        Schema::dropIfExists('therapist_profiles');
    }
};
