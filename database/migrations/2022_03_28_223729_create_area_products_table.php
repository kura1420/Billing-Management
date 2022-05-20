<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('area_id');
            $table->foreignUuid('provinsi_id');
            $table->foreignUuid('city_id');
            $table->foreignUuid('product_id');
            $table->boolean('active')->default(0);
            $table->boolean('with_promo')->default(0);
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
        Schema::dropIfExists('area_products');
    }
}
