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
        Schema::create('marche_publics_categories', function (Blueprint $table) {
            $table->unsignedInteger('categorie_id');
            $table->unsignedInteger('marche_public_id');
            $table->primary(['categorie_id','marche_public_id']);
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
        Schema::dropIfExists('marche_publics_categories');
    }
};
