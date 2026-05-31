<?php

namespace Database\Factories;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Bid> */
class BidFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory()->published(),
            'user_id' => User::factory(),
            'amount' => fake()->randomFloat(2, 10000, 4000000),
            'technical_proposal' => fake()->paragraphs(3, true),
            'financial_proposal' => fake()->paragraphs(2, true),
            'status' => 'pending',
            'notes' => null,
            'submitted_at' => now(),
        ];
    }
}
