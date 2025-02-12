<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'employee_id' => Employee::factory(), // Ensure employee exists
            'photo' => $this->faker->imageUrl(),
        ];
    }
}


