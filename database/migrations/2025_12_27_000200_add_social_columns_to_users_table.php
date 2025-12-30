<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_name')->nullable()->after('role');
            $table->string('google_id')->nullable()->after('provider_name')->unique();
            $table->string('facebook_id')->nullable()->after('google_id')->unique();
            $table->string('avatar_url')->nullable()->after('facebook_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provider_name', 'google_id', 'facebook_id', 'avatar_url']);
        });
    }
};
