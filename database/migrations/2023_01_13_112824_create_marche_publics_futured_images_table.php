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
        Schema::create('marche_publics_futured_images', function (Blueprint $table) {
            $table->unsignedInteger('marche_public_id');
            $table->unsignedInteger('fichier_id');
            $table->primary(['marche_public_id','fichier_id'],'marche_fichier_id');
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
        Schema::dropIfExists('marche_publics_futured_images');
    }
};
