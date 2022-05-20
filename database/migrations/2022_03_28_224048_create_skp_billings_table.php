<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkpBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_billings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('date');
            $table->decimal('price_ppn', 12, 2);
            $table->decimal('price_pph', 12, 2);
            $table->decimal('price_sub', 12, 2);
            $table->decimal('price_total', 12, 2);
            $table->boolean('status')->default(0);
            $table->dateTime('notif_at');
            $table->dateTime('suspend_at');
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
        Schema::dropIfExists('skp_billings');
    }
}
