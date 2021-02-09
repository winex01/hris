<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffenceAndSanctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offence_and_sanctions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->date('date_issued');
            $table->foreignId('offence_classification_id')->nullable()->constrained();
            $table->foreignId('gravity_of_sanction_id')->nullable()->constrained();
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
        Schema::dropIfExists('offence_and_sanctions');
    }
}
