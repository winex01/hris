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

            $table->date('date_evaluated')->nullable();
            $table->foreignId('appraisal_type_id')->constrained();

            // name of appraiser -- employee
            $table->unsignedBigInteger('appraiser_id');
            $table->foreign('appraiser_id')->references('id')->on('employees')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            // individual performance
            $table->float('job_function')->default(0);
            $table->float('productivity')->default(0);
            $table->float('attendance')->default(0);

            // job competencies
            $table->float('planning_and_organizing')->default(0);
            $table->float('innovation')->default(0);
            $table->float('technical_domain')->default(0);

            // organizational competencies
            $table->float('sense_of_ownership')->default(0);
            $table->float('customer_relation')->default(0);
            $table->float('professional_conduct')->default(0);

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
