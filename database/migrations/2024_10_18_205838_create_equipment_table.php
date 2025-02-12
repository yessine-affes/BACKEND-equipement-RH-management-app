<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['Pending', 'InProgress', 'Completed']);
            $table->string('type');
            $table->boolean('availability');
            $table->string('photo')->nullable();
            $table->json('maintenanceSchedule')->nullable(); // Use json type here
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}

