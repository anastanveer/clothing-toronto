<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('requires_assignment')
                ->default(false)
                ->after('expires_at');
            $table->unsignedInteger('max_assignments')
                ->nullable()
                ->after('requires_assignment');
            $table->unsignedInteger('priority')
                ->default(0)
                ->after('max_assignments');
            $table->string('audience_tag')
                ->nullable()
                ->after('priority');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'requires_assignment',
                'max_assignments',
                'priority',
                'audience_tag',
            ]);
        });
    }
};
