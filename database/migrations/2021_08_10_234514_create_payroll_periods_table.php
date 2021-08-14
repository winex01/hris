<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('year_month');
            $table->date('payroll_start');
            $table->date('payroll_end');

            $table->boolean('deduct_pagibig');
            $table->boolean('deduct_philhealth');
            $table->boolean('deduct_sss');

            // TODO:: temporay witholding tax basis i put boolean, change to relationship(create wth crud first).
            $table->boolean('witholding_tax_basis');
            $table->foreignId('grouping_id')->nullable()->constrained();
            $table->boolean('is_last_pay')->default(false);

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
        Schema::dropIfExists('payroll_periods');
    }
}
