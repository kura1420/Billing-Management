<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 100)->unique();
            $table->string('name', 255);
            $table->string('serial_numbers', 255)->nullable();
            $table->text('spec');
            $table->text('desc')->nullable();
            $table->integer('year_release', false, false)->nullable();
            $table->char('picture', 50);
            $table->char('qrcode', 50)->comment('filename qrcode');
            $table->decimal('last_price', 12,2)->default(0);
            $table->foreignId('brand_id');
            $table->foreignUuid('partner_id');
            $table->foreignId('unit_id');
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
        Schema::dropIfExists('items');
    }
}
