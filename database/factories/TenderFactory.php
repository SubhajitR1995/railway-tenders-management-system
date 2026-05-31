<?php

namespace Database\Factories;

use App\Models\Tender;
use App\Models\TenderCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Tender> */
class TenderFactory extends Factory
{
    private static int $sequence = 0;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        self::$sequence++;
        $year = date('Y');

        return [
            'tender_number' => sprintf('RLW-%s-%04d', $year, self::$sequence),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraphs(3, true),
            'requirements' => fake()->paragraphs(2, true),
            'category_id' => TenderCategory::factory(),
            'created_by' => User::factory()->manager(),
            'budget' => fake()->randomFloat(2, 50000, 5000000),
            'submission_deadline' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'status' => fake()->randomElement(['draft', 'published', 'closed']),
            'awarded_bid_id' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'submission_deadline' => fake()->dateTimeBetween('+1 week', '+3 months'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'submission_deadline' => fake()->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }
}
