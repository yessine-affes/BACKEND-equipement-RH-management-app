<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id'); // Ensure it is unsignedBigInteger
            $table->unsignedBigInteger('employee_id'); // Ensure it is unsignedBigInteger
            $table->unsignedBigInteger('equipment_id')->nullable(); // Ensure it is unsignedBigInteger

            $table->date('assigned_date');
            $table->date('completion_date')->nullable();
            $table->enum('status', ['Pending', 'InProgress', 'Completed']);

            // Foreign Key Constraints
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_assignments');
    }
}
