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
        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('currency')->default('EUR');
            
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('track_inventory')->default(false);
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->nullable();
            
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->json('images')->nullable();
            $table->string('thumbnail')->nullable();
            
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);
            
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
