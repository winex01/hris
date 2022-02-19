<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApproverLeaveappPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approver_leaveapp', function (Blueprint $table) {
            $table->unsignedBigInteger('approver_id')->index();
            $table->foreign('approver_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('leaveapp_id')->index();
            $table->foreign('leaveapp_id')->references('id')->on('leave_applications')->onDelete('cascade');
            $table->primary(['approver_id', 'leaveapp_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approver_leaveapp');
    }
}
