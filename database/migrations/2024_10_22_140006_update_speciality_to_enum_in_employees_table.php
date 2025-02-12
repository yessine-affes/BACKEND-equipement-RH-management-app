<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSpecialityToEnumInEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to create the ENUM type
        DB::statement("ALTER TABLE employees MODIFY speciality ENUM('Mining', 'Processing', 'Transport', 'QualityControl', 'Maintenance') DEFAULT 'Mining'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // In case you want to revert the change
        DB::statement("ALTER TABLE employees MODIFY speciality VARCHAR(255) DEFAULT NULL");
    }
}
