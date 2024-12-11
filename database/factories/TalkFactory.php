<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Speaker;
use App\Models\Talk;

class TalkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Talk::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'abstract' => $this->faker->text(),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'speaker_id' => Speaker::factory(),
        ];
    }
}
