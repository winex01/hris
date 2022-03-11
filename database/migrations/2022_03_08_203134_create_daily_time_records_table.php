<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyTimeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_time_records', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade'); 
            
            $table->date('date');
            
            // TODO:: shift dataType = json to retain value even if shift_schedule is modified
            $table->json('shift')->nullable(); // TODO:: TBD
            
            // TODO:: dtr logs TBD json
            $table->json('logs')->nullable(); // TODO:: TBD

            // TODO:: leave TBD nullable fk


            // TODO:: reg hour TBD varchar just like relative_day_start hh:mm
            // TODO:: late TBD varchar hh:mm
            // TODO:: UT TBD varchar hh:mm
            // TODO:: OT TBD varchar hh:mm
            // TODO:: POT TBD varcahr hh:mm
            
            // TODO:: https://github.com/winex01/hris/issues/176
            // TODO:: dont forget to override calendar display to dtr shift schedule,
            // TODO:: modify method shiftSchedule/etc if dtrSHift is not empty then, override empShift/changeShift
            // TODO:: add tooltipe/title to shift column when row is hover and display all shit_schedule details.

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
        Schema::dropIfExists('daily_time_records');
    }
}
