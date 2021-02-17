<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->boolean('open_time')->default(0);
            $table->json('working_hours')->nullable();
            $table->json('overtime_hours')->nullable();
            $table->boolean('dynamic_break')->default(0)->nullable();
            $table->string('dynamic_break_credit')->nullable();
            $table->text('description')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_schedules');
    }
}
