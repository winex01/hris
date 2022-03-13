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
            
            // TODO:: https://github.com/winex01/hris/issues/176
            // TODO:: shift_schedule TBD dont create column instead display custom col in list base on employee and shift date
            // TODO:: dtr logs TBD no migration column only custom display col in list
            // TODO:: leave TBD migration column only custom display col in list
            // TODO:: reg hour varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
            // TODO:: late varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
            // TODO:: UT varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
            // TODO:: OT varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
            // TODO:: POT hh:mm, no migration col, custom col display in list
            // TODO:: TBD add tooltip/title to shift column when row is hover and display all shit_schedule details.
            // TODO:: add tooltip/title to other column rows.

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
