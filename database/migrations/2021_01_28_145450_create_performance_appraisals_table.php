<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_appraisals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');

            // TODO::
            $table->date('date_evaluated')->nullable();
            // type
            
            // individual performance
                // job function
                // productivity
                // attendance

            // job competencies
                // planning & organizing
                // innovation
                // technical domain

            // organizational competencies
                // sense of ownership
                // customer relation
                // professional conduct

            // name of appraiser -- employee
            // total rating
            // interpretation
            // attachment



            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('performance_appraisals');
    }
}
