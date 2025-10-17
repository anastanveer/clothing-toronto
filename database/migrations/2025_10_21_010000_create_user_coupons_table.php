<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('available');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('available_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'coupon_id']);
            $table->index(['user_id', 'status']);
            $table->index(['coupon_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
    }
};
