<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernmentExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('government_examinations', function (Blueprint $table) {
            $table->id();

             $table->foreignId('employee_id')
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->string('title')->nullable();
            $table->string('institution')->nullable();
            $table->date('date')->nullable();
            $table->text('venue')->nullable();
            $table->string('rating')->nullable();
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
        Schema::dropIfExists('government_examinations');
    }
}
