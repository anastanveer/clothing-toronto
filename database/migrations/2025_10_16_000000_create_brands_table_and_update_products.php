<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('tagline')->nullable();
                $table->string('hero_image')->nullable();
                $table->text('summary')->nullable();
                $table->longText('description')->nullable();
                $table->boolean('is_published')->default(true);
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('brand_id')->nullable()->after('category')->constrained('brands')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('products', 'brand')) {
            $brands = DB::table('products')
                ->select('brand')
                ->whereNotNull('brand')
                ->distinct()
                ->pluck('brand');

            foreach ($brands as $name) {
                $name = trim((string) $name);

                if ($name === '') {
                    continue;
                }

                $existing = DB::table('brands')->where('name', $name)->first();

                if (! $existing) {
                    $slug = Str::slug($name);
                    $originalSlug = $slug;
                    $i = 1;

                    while (DB::table('brands')->where('slug', $slug)->exists()) {
                        $slug = $originalSlug . '-' . $i++;
                    }

                    DB::table('brands')->insert([
                        'name' => $name,
                        'slug' => $slug,
                        'summary' => null,
                        'description' => null,
                        'is_published' => true,
                        'meta' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $brandIdByName = DB::table('brands')->pluck('id', 'name');

            DB::table('products')->whereNotNull('brand')->orderBy('id')->chunkById(500, function ($chunk) use ($brandIdByName) {
                foreach ($chunk as $product) {
                    $name = trim((string) $product->brand);
                    $brandId = $brandIdByName[$name] ?? null;

                    if ($brandId) {
                        DB::table('products')->where('id', $product->id)->update([
                            'brand_id' => $brandId,
                        ]);
                    }
                }
            });

            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('brand');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['brand_id']);
                $table->dropColumn('brand_id');
            });
        }

        if (Schema::hasTable('brands')) {
            Schema::dropIfExists('brands');
        }
    }
};
