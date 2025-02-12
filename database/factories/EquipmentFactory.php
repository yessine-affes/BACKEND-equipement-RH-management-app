<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'status' => $this->faker->randomElement(['Pending', 'InProgress', 'Completed']),
            'type' => $this->faker->word,
            'availability' => $this->faker->boolean,
            'photo' => $this->faker->imageUrl(),
            'maintenanceSchedule' => json_encode([$this->faker->date, $this->faker->date]),
        ];
    }
}

