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
        Schema::create('gestion_rhs', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->longText('objet')->nullable();
            $table->string('secteur')->nullable();
            $table->string('categorie')->nullable();
            $table->string('date_publication')->nullable();
            $table->string('date_limite')->nullable();
            $table->string('futured_image')->nullable();
            $table->string('lien')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('gestion_rhs');
    }
};
