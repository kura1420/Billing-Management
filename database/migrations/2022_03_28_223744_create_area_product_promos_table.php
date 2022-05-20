<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaProductPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_product_promos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('area_id');
            $table->foreignUuid('provinsi_id');
            $table->foreignUuid('city_id');
            $table->foreignUuid('area_product_id');
            $table->foreignUuid('product_promo_id');
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('area_product_promos');
    }
}
