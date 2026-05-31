<?php

namespace Database\Seeders;

use App\Models\Bid;
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

        // Bidder users
        $bidders = User::factory(5)->create();

        // Categories
        $categories = TenderCategory::factory(8)->create();

        // Published tenders
        $publishedTenders = Tender::factory(6)->published()->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by' => $manager->id,
        ]);

        // Draft tenders
        Tender::factory(3)->draft()->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by' => $manager->id,
        ]);

        // Closed tenders with bids
        $closedTenders = Tender::factory(3)->closed()->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by' => $manager->id,
        ]);

        // Add bids to published tenders
        foreach ($publishedTenders as $tender) {
            foreach ($bidders->random(3) as $bidder) {
                Bid::factory()->create([
                    'tender_id' => $tender->id,
                    'user_id' => $bidder->id,
                ]);
            }
        }

        // Add bids to closed tenders and award one
        foreach ($closedTenders as $tender) {
            $bids = collect();
            foreach ($bidders as $bidder) {
                $bid = Bid::factory()->create([
                    'tender_id' => $tender->id,
                    'user_id' => $bidder->id,
                    'status' => 'pending',
                ]);
                $bids->push($bid);
            }

            // Award to the lowest bidder
            $lowestBid = $bids->sortBy('amount')->first();
            $lowestBid->update(['status' => 'accepted']);
            $tender->update([
                'status' => 'awarded',
                'awarded_bid_id' => $lowestBid->id,
            ]);
        }
    }
}
