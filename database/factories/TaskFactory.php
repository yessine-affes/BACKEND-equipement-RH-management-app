<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'due_date' => $this->faker->date,
            'project_id' => \App\Models\Project::factory(),
            'status' => $this->faker->randomElement(['Pending', 'InProgress', 'Completed']),
        ];
    }
}

