<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloodTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blood_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('personal_datas', function (Blueprint $table) {
            $table->foreignId('blood_type_id')
            ->after('civil_status_id')
            ->nullable()
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_datas', function (Blueprint $table) {
            $table->dropForeign(['blood_type_id']);
            $table->dropColumn('blood_type_id');
        });
        
        Schema::dropIfExists('blood_types');
    }
}
