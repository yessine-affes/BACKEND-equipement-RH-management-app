<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Project;
use App\Models\Task;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Certification;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admins
        Admin::factory(10)->create();

        // Create Projects
        Project::factory(20)->create();

        // Create Tasks
        Task::factory(50)->create();

        // Create Employees
        Employee::factory(30)->create();

        // Create Equipment
        Equipment::factory(40)->create();

        // Create Certifications
        Certification::factory(100)->create();

        // Create Reports
        Report::factory(50)->create();

        // Note: Remove or comment out TaskAssignment seeding until the issue is resolved
        // TaskAssignment::factory(50)->create();
        Report::factory(50)->create(); // Create 50 reports
    }
}


