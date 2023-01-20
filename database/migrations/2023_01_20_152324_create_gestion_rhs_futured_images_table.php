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
        Schema::create('gestion_rhs_futured_images', function (Blueprint $table) {
            $table->unsignedInteger('gestion_rh_id');
            $table->unsignedInteger('fichier_id');
            $table->primary(['gestion_rh_id','fichier_id'],'grh_fichier_id');
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
        Schema::dropIfExists('gestion_rhs_futured_images');
    }
};
