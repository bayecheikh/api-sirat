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
        Schema::create('monnaies_ligne_financements', function (Blueprint $table) {
            $table->unsignedInteger('monnaie_id');
            $table->unsignedInteger('ligne_financement_id');
            $table->primary(['monnaie_id','ligne_financement_id'],'monnaie_ligne_id');
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
        Schema::dropIfExists('monnaies_ligne_financements');
    }
};
