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
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand_key', 80)->default('toronto-textile')->after('shopify_id');
            $table->dropUnique('products_shopify_id_unique');
            $table->dropUnique('products_handle_unique');
            $table->unique(['brand_key', 'shopify_id']);
            $table->unique(['brand_key', 'handle']);
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->string('brand_key', 80)->default('toronto-textile')->after('shopify_id');
            $table->dropUnique('collections_shopify_id_unique');
            $table->dropUnique('collections_handle_unique');
            $table->unique(['brand_key', 'shopify_id']);
            $table->unique(['brand_key', 'handle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_brand_key_shopify_id_unique');
            $table->dropUnique('products_brand_key_handle_unique');
            $table->dropColumn('brand_key');
            $table->unique('shopify_id');
            $table->unique('handle');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropUnique('collections_brand_key_shopify_id_unique');
            $table->dropUnique('collections_brand_key_handle_unique');
            $table->dropColumn('brand_key');
            $table->unique('shopify_id');
            $table->unique('handle');
        });
    }
};
