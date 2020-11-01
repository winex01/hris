<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCivilStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civil_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('personal_datas', function (Blueprint $table) {
            $table->foreignId('civil_status_id')
                ->after('tin')
                ->nullable()
                ->constrained(); 
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
            $table->dropForeign(['civil_status_id']);
            $table->dropColumn('civil_status_id');
        });

        Schema::dropIfExists('civil_statuses');
    }
}
