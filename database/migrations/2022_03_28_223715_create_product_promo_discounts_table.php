<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPromoDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_promo_discounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('type', 20)->comment('payment, service');
            $table->integer('discount', false, false)->comment('value with percent');
            $table->double('price', 10, 2)->comment('value in price');
            $table->integer('until_payment', false, false)->default(0);
            $table->foreignUuid('product_promo_id');
            $table->foreignUuid('product_id')->nullable();
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
        Schema::dropIfExists('product_promo_discounts');
    }
}
