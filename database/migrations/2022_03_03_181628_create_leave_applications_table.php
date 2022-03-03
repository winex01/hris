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

            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->foreignId('leave_type_id')->constrained();

            $table->date('date');
            $table->float('credit_unit'); // 1 = whole_day, .5 = half_day 
            
            $table->smallInteger('status')->default(0); // 0 = pending, 1 = approved, 2 = denied
            $table->integer('approved_level')->nullable(); // current leave approver level

            $table->foreignId('leave_approver_id')->nullable()->constrained();

            $table->text('description')->nullable();
            $table->string('attachment')->nullable();

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
