<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educational_backgrounds', function (Blueprint $table) {
            $table->id();
             $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            

            // TODO::
            // Educational Level - string - Select an educational level.
            //      elementary
            //      high school
            //      vocational school
            //       tertiary / college
            //      Masters / Doctorate
            // Course / Major - string - Enter the course or major taken.
            // school - string- Enter the name of school.
            // Address - textarea - Enter the address of school.
            // date from - date - Enter the start of school year.
            // date to - Enter the end of school year.
            // attachment



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
        Schema::dropIfExists('educational_backgrounds');
    }
}
