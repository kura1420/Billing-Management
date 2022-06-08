<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTransactionModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_transaction_modes', function (Blueprint $table) {
            $table->foreignUuid('billing_invoice_id');
            $table->char('mode', 50);
            $table->string('mode_id', 255);
            $table->string('mode_portal', 255)->nullable();
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
        Schema::dropIfExists('invoice_transaction_modes');
    }
}
