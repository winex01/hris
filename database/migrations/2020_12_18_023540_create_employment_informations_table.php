<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            

            // TODO::
            // company - select
            // location - select
                // ex. manila,
                // cebu
                // etc.
            // department - select
                // ex. Merchandising
                // store operation
                // etc.
            // division - select
            // section - select
            // position - select
            // level -select
            // rank - select
            // employment status - select
                // contractual
                // probationary
                // regular
                // special project 
                // trainee / intern
            // job status - select
                // active
                // end of contract
                // forced leave
                // inactive
                // resigned
                // retired
                // terminated
            // days per year - select
                // 262.0000/5.0000/8.0000
                // 312.0000/5.0000/8.0000
                // 313.0000/5.0000/8.0000
                // 313.0000/6.0000/8.0000
                // 314.0000/6.0000/8.0000
                // 360.0000/5.0000/8.0000
                // 365.0000/7.0000/8.0000
            // pay basis- select
                // monthly paid
                // pro-rated monthly maid
                // daily paid
                // hourly paid
            // basic rate - double - enter the basic rate amount.
            // ecola - double
            // basic - adjustment - double
            // tax code - select
            // grouping - (payroll group man siguro)
            // payment method - select
                // bank (ATM)
                // cash
                // check
            // effectivity date - date




            // $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_informations');
    }
}
