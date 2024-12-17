<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendee>
 */
class AttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail(),
            'ticket_cost' => 1000,
            'is_paid' => $this->faker->randomElement([true, false]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
