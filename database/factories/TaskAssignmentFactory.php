<?php

namespace Database\Factories;

use App\Models\TaskAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskAssignmentFactory extends Factory
{
    protected $model = TaskAssignment::class;

    public function definition()
    {
        return [
            'task_id' => \App\Models\Task::factory(),
            'employee_id' => \App\Models\Employee::factory(),
            'equipment_id' => \App\Models\Equipment::factory(),
            'assigned_date' => $this->faker->date,
            'completion_date' => $this->faker->date,
            'status' => $this->faker->randomElement(['Pending', 'InProgress', 'Completed']),
        ];
    }
}

