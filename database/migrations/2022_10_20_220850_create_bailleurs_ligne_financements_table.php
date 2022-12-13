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
        Schema::create('bailleurs_ligne_financements', function (Blueprint $table) {
            $table->unsignedInteger('bailleur_id');
            $table->unsignedInteger('ligne_financement_id');
            $table->primary(['bailleur_id','ligne_financement_id'],'bailleur_ligne_id');
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
        Schema::dropIfExists('bailleurs_ligne_financements');
    }
};
