<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 50)->unique();
            $table->boolean('active')->default(0);
            $table->dateTime('member_at')->nullable();
            $table->dateTime('terminate_at')->nullable();
            $table->dateTime('dismantle_at')->nullable();
            $table->foreignUuid('customer_type_id');
            $table->foreignUuid('customer_segment_id');
            $table->foreignUuid('area_id');
            $table->foreignUuid('product_id');
            $table->foreignUuid('provinsi_id');
            $table->foreignUuid('city_id');
            $table->foreignUuid('area_product_id');
            $table->foreignUuid('area_product_customer_id');
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
        Schema::dropIfExists('customer_data');
    }
}
