<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            // TODO:: fix this
            // note:: ex. spouse/father/emergency etc.
            $table->string('relation'); 

            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();

            $table->string('mobile_number')->nullable();
            $table->string('telephone_number')->nullable();

            $table->string('company_email')->nullable();
            $table->string('personal_email')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable(); 
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable(); 

            $table->string('occupation')->nullable(); 
            $table->string('company')->nullable(); 
            $table->string('company_address')->nullable(); 

            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            $table->bigInteger('personable_id');
            $table->string('personable_type');
            
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
        Schema::dropIfExists('persons');
    }
}
