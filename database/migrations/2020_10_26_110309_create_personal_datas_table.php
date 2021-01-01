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
        // TODO:: remove this
        Schema::create('personal_datas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade'); 

            $table->string('mobile_number')->nullable();
            $table->string('telephone_number')->nullable();

            $table->string('company_email')->nullable();
            $table->string('personal_email')->nullable();

            $table->string('pagibig')->nullable();
            $table->string('sss')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('tin')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable(); 
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable(); 

            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            $table->date('date_applied')->nullable();                
            $table->date('date_hired')->nullable();   
            
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
