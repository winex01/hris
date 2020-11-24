<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizenshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizenships', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('personal_datas', function (Blueprint $table) {
            $table->foreignId('citizenship_id')
            ->after('civil_status_id')
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
            $table->dropForeign(['citizenship_id']);
            $table->dropColumn('citizenship_id');
        });
        
        Schema::dropIfExists('citizenships');
    }
}
