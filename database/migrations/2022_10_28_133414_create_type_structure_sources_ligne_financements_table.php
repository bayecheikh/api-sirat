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
        Schema::create('type_structure_sources_ligne_financements', function (Blueprint $table) {
            $table->unsignedInteger('type_structure_source_id');
            $table->unsignedInteger('ligne_financement_id');
            $table->primary(['type_structure_source_id','ligne_financement_id'],'source_ligne_id');
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
        Schema::dropIfExists('type_structure_sources_ligne_financements');
    }
};
