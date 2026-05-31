<?php

namespace Database\Factories;

use App\Models\TenderCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TenderCategory> */
class TenderCategoryFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $categories = [
            'Infrastructure' => 'Civil works including tracks, bridges, tunnels, and stations.',
            'Rolling Stock' => 'Procurement of locomotives, wagons, and passenger coaches.',
            'Signaling & Communication' => 'Signal systems, SCADA, and communication infrastructure.',
            'Electrification' => 'Overhead lines, substations, and power supply systems.',
            'IT Systems' => 'Software, hardware, and digital transformation projects.',
            'Maintenance Services' => 'Preventive and corrective maintenance contracts.',
            'Consulting & Engineering' => 'Feasibility studies, design, and project management.',
            'Safety Equipment' => 'PPE, safety systems, and emergency response equipment.',
        ];

        $name = fake()->unique()->randomElement(array_keys($categories));

        return [
            'name' => $name,
            'description' => $categories[$name],
        ];
    }
}
