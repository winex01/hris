<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_datas', function (Blueprint $table) {
            $table->id();

            $table->string('address')->nullable();
            $table->string('city')->nullable(); 
            $table->string('zip_code')->nullable(); 
            $table->string('country')->nullable();

            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            $table->string('mobile_number')->nullable();
            $table->string('telephone_number')->nullable();

            $table->string('personal_email')->nullable();
            $table->string('company_email')->nullable();

            $table->string('pagibig')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('sss')->nullable();
            $table->string('tin')->nullable();

            // Gender --create table
            // Citizenship --create table
            // Religion --create table
            
            $table->date('date_applied')->nullable();                
            $table->date('date_hired')->nullable();      

            $table->foreignId('employee_id')->constrained(); 

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
        Schema::dropIfExists('personal_datas');
    }
}
