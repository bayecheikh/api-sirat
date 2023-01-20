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
        Schema::create('gestion_rhs_categories', function (Blueprint $table) {
            $table->unsignedInteger('categorie_id');
            $table->unsignedInteger('gestion_rh_id');
            $table->primary(['categorie_id','gestion_rh_id']);
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
        Schema::dropIfExists('gestion_rhs_categories');
    }
};
