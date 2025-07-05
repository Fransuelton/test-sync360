<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'profile_image' => $this->faker->imageUrl(),
            'full_name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(18, 80),
            'street' => $this->faker->streetAddress(),
            'neighborhood' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'biography' => $this->faker->paragraph(),
        ];
    }
}

