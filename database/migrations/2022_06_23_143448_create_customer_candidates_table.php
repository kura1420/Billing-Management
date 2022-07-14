<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 255);
            $table->string('email', 100)->unique();
            $table->char('handphone', 14)->unique();
            $table->char('file', 50);
            $table->text('address');
            $table->text('longitude')->nullable();
            $table->text('latitude')->nullable();
            $table->char('status', 1)->default(0);
            $table->char('from', '50');
            $table->foreignId('user_id')->nullable();
            $table->foreignUuid('area_id');
            $table->foreignUuid('provinsi_id');
            $table->foreignUuid('city_id');
            $table->foreignUuid('product_type_id');
            $table->foreignUuid('product_service_id');
            $table->foreignUuid('area_product_id');
            $table->foreignUuid('area_product_promo_id')->nullable();
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
        Schema::dropIfExists('customer_candidates');
    }
}
