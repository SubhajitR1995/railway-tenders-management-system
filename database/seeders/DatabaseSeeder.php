<?php

namespace Database\Seeders;

use App\Models\Tender;
use App\Models\TenderCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::factory()->admin()->create([
            'name' => 'System Admin',
            'email' => 'admin@railway.com',
            'password' => Hash::make('password'),
        ]);

        // Manager user
        $manager = User::factory()->manager()->create([
            'name' => 'Tender Manager',
            'email' => 'manager@railway.com',
            'password' => Hash::make('password'),
        ]);

        // Contractor users
        User::factory(5)->create();

        // Categories
        $categories = TenderCategory::factory(8)->create();

        // Published tenders
        Tender::factory(6)->published()->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by' => $manager->id,
        ]);

        // Draft tenders
        Tender::factory(3)->draft()->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by' => $manager->id,
        ]);
    }
}
