<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();

            // $table->foreignId('employee_id')
            // ->constrained()
            // ->onDelete('cascade')
            // ->onUpdate('cascade'); // TODO:: relationship

            //$table->foreignId('leave_type_id')->constrained();

            // TODO::
            //$table->date('date');
            //$table->float('credit_unit'); // 1 = whole_day, .5 = half_day // TODO:: add validation that only accepts 1 and .5
            // $table->foreign('applied_by_id')->references('id')->on('employees'); // TODO:: relationship
            //$table->boolean('status')->default(0); // 0 = pending, 1 = approved
            // $table->foreign('last_approved_by_id')->references('id')->on('employees'); // TODO:: relationship

            // TODO:: create employee leave approver CRUD
            // approvers = list of all approvers
            
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
        Schema::dropIfExists('leave_applications');
    }
}
