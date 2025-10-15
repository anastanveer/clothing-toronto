<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@glamer.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Anas12345!@#$%'),
                'is_admin' => true,
            ]
        );

        foreach (Product::CATEGORIES as $category) {
            Product::factory()
                ->count(8)
                ->create(['category' => $category]);
        }

        BlogPost::factory()
            ->count(8)
            ->create([
                'author_id' => $admin->id,
            ]);
    }
}
