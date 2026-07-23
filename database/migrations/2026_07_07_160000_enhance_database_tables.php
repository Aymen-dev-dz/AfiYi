<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing columns to therapist_profiles
        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_issuer')->nullable();
            $table->string('status')->default('pending'); // pending, active, approved, rejected
            $table->json('approaches')->nullable();
            $table->string('photo')->nullable();
            $table->integer('experience_years')->nullable();
            $table->decimal('session_price', 8, 2)->nullable();
            $table->integer('session_duration_minutes')->default(60);
            $table->string('currency')->default('EUR');
            $table->integer('max_clients')->nullable();
            $table->boolean('accepts_new_clients')->default(true);
            $table->boolean('offers_first_free_session')->default(false);
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('total_sessions')->default(0);
            $table->integer('total_reviews')->default(0);
            $table->string('daily_room_prefix')->nullable();
            $table->string('stripe_account_id')->nullable();
            $table->boolean('stripe_onboarding_complete')->default(false);
            $table->boolean('stripe_charges_enabled')->default(false);
            $table->boolean('stripe_payouts_enabled')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
        });

        // 2. Create therapist_reviews table
        Schema::create('therapist_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('therapist_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // 3. Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, paid, failed, refunded
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('EUR');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });

        // 4. Create refunds table
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('reason')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });

        // 5. Create shipments table
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('status')->default('pending'); // pending, transit, delivered
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // 6. Create shipment_events table
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();
        });

        // 7. Create commissions table
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('percentage', 5, 2);
            $table->timestamps();
        });

        // 8. Create order_status_histories table
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('from_status');
            $table->string('to_status');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // 9. Create anonymous_chat_ratings table
        Schema::create('anonymous_chat_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('destiny_matches')->cascadeOnDelete();
            $table->foreignId('rated_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rater_user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('rating'); // 1 = helpful/positive, 0 = negative
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anonymous_chat_ratings');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('shipment_events');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('therapist_reviews');

        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'license_number', 'license_issuer', 'status', 'approaches', 'photo',
                'experience_years', 'session_price', 'session_duration_minutes', 'currency',
                'max_clients', 'accepts_new_clients', 'offers_first_free_session', 'rating',
                'total_sessions', 'total_reviews', 'daily_room_prefix', 'stripe_account_id',
                'stripe_onboarding_complete', 'stripe_charges_enabled', 'stripe_payouts_enabled',
                'verified_at', 'approved_at', 'rejection_reason'
            ]);
        });
    }
};
