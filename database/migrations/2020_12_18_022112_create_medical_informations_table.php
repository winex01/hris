<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_informations', function (Blueprint $table) {
            $table->id();
             $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            

            // TODO::
            // Medical examination / History - string - Enter the type of medical information.
            // Date taken - date - Enter the date of medical examination.
            // Expiration date - date -Enter the expiration date of examination.
            // diagnosis - textarea



            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_informations');
    }
}
