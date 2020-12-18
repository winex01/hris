<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childrens', function (Blueprint $table) {
            $table->id();
             $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->string('child_name')->nullable(); # Enter the name of child.
            $table->date('birth_date')->nullable(); # Enter the birth date of child.
            $table->string('birth_place')->nullable(); # Enter the birth place of child.

            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // TODO:: create crud
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('childrens');
    }
}
