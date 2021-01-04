<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyRelationsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_relations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('family_datas', function (Blueprint $table) {
            $table->foreignId('family_relation_id')
                ->after('employee_id')
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
        Schema::table('family_datas', function (Blueprint $table) {
            $table->dropForeign(['family_relation_id']);
            $table->dropColumn('family_relation_id');
        });

        Schema::dropIfExists('family_relations');
    }
}
