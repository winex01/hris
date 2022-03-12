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
            
            // TODO:: shift_schedule fk
            // TODO:: dtr logs TBD pivot or Join using date and employee between dtr table and dtr logs table or type hidden and display logs in pragraph
            // TODO:: leave fk nullable
            // TODO:: reg hour varchar hh:mm nullable
            // TODO:: late varchar hh:mm nullable
            // TODO:: UT varchar hh:mm nullable
            // TODO:: OT varchar hh:mm nullable
            // TODO:: POT varcahr hh:mm nullable
            
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
