<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWithholdingTaxVersionIdToPayrollPeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->foreignId('withholding_tax_version_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_periods', function (Blueprint $table) {
            // 1. Drop foreign key constraints
            $table->dropForeign(['withholding_tax_version_id']);

            // 2. Drop the column
            $table->dropColumn('withholding_tax_version_id');
        });
    }
}
