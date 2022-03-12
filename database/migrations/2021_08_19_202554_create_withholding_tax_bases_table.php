<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithholdingTaxBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholding_tax_bases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('payroll_periods', function (Blueprint $table) {
            $table->foreignId('withholding_tax_basis_id')->after('deduct_sss')->constrained();
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
            $table->dropForeign(['withholding_tax_basis_id']);
            $table->dropColumn('withholding_tax_basis_id');
        });
        
        Schema::dropIfExists('withholding_tax_bases');
    }
}
