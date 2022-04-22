<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaProductCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_product_customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('provinsi_id');
            $table->foreignUuid('city_id');
            $table->foreignUuid('area_id');
            $table->foreignUuid('area_product_id');
            $table->foreignUuid('customer_type_id');
            $table->foreignUuid('customer_segment_id');
            $table->boolean('active')->default(0);
            $table->decimal('price_ppn', 10, 2);
            $table->decimal('price_pph', 10, 2);
            $table->decimal('price_total', 12, 2);
            $table->decimal('price_discount', 12, 2);
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
        Schema::dropIfExists('area_product_customers');
    }
}
