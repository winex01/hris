<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_approvers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->json('approvers')->nullable();
            $table->date('effectivity_date');

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
        Schema::dropIfExists('leave_approvers');
    }
}
