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
        Schema::create('investissements_bailleurs', function (Blueprint $table) {
            $table->unsignedInteger('investissement_id');
            $table->unsignedInteger('bailleur_id');
            $table->primary(['investissement_id','bailleur_id']);
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
        Schema::dropIfExists('investissements_bailleurs');
    }
};
