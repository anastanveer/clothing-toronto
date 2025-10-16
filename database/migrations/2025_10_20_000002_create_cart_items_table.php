<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('selected_size')->nullable();
            $table->string('selected_color')->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();
            $table->unique(['user_id', 'product_id', 'selected_size', 'selected_color'], 'cart_unique_variant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
