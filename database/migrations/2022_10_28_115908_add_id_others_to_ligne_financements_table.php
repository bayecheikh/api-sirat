<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ligne_financements', function (Blueprint $table) {
            $table->string('id_investissement')->nullable();
            $table->string('id_structure')->nullable();
            $table->string('id_annee')->nullable();
            $table->string('id_monnaie')->nullable();
            $table->string('id_dimension')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ligne_financements', function (Blueprint $table) {
            //
        });
    }
};
