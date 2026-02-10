<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique('product_variants_shopify_id_unique');
            $table->unique(['product_id', 'shopify_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropUnique('product_images_shopify_id_unique');
            $table->unique(['product_id', 'shopify_id']);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique('product_variants_product_id_shopify_id_unique');
            $table->unique('shopify_id');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropUnique('product_images_product_id_shopify_id_unique');
            $table->unique('shopify_id');
        });
    }
};
