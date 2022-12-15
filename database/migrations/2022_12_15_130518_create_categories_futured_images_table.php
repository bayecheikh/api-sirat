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
        Schema::create('categories_futured_images', function (Blueprint $table) {
            $table->unsignedInteger('categorie_id');
            $table->unsignedInteger('futured_image_id');
            $table->primary(['categorie_id','futured_image_id']);
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
        Schema::dropIfExists('categories_futured_images');
    }
};
