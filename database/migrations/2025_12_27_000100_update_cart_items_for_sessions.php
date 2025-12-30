<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items_tmp', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('selected_size')->nullable();
            $table->string('selected_color')->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'product_id', 'selected_size', 'selected_color'], 'cart_user_variant_unique');
            $table->unique(['session_id', 'product_id', 'selected_size', 'selected_color'], 'cart_session_variant_unique');
        });

        DB::statement('
            INSERT INTO cart_items_tmp (id, user_id, product_id, quantity, selected_size, selected_color, added_at, created_at, updated_at)
            SELECT id, user_id, product_id, quantity, selected_size, selected_color, added_at, created_at, updated_at
            FROM cart_items
        ');

        Schema::drop('cart_items');
        Schema::rename('cart_items_tmp', 'cart_items');
    }

    public function down(): void
    {
        Schema::create('cart_items_old', function (Blueprint $table) {
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

        DB::statement('
            INSERT INTO cart_items_old (id, user_id, product_id, quantity, selected_size, selected_color, added_at, created_at, updated_at)
            SELECT id, user_id, product_id, quantity, selected_size, selected_color, added_at, created_at, updated_at
            FROM cart_items
            WHERE user_id IS NOT NULL
        ');

        Schema::drop('cart_items');
        Schema::rename('cart_items_old', 'cart_items');
    }
};
