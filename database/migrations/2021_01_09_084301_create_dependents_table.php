<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('relation_id')
                ->nullable()
                ->constrained();
            
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

            $table->string('occupation')->nullable(); 
            $table->string('company')->nullable(); 
            $table->string('company_address')->nullable(); 

            $table->date('birth_date')->nullable();

            $table->boolean('disability')->nullable()->default(0);

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
        Schema::dropIfExists('dependents');
    }
}
