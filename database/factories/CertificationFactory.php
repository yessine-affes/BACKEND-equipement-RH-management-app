<?php

namespace Database\Factories;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificationFactory extends Factory
{
    protected $model = Certification::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'issuer' => $this->faker->company,
            'issue_date' => $this->faker->date,
            'expiry_date' => $this->faker->date,
            'employee_id' => \App\Models\Employee::factory(),
        ];
    }
}

