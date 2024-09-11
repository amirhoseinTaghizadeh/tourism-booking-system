<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'available_slots' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl(),
            'start_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
        ];
    }
}
