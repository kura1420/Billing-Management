<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('billing_type_id');
            $table->foreignUuid('billing_profile_id');
            $table->foreignUuid('customer_data_id');
            $table->dateTime('notif_at');
            $table->dateTime('suspend_at');
            $table->dateTime('terminate_at');
            $table->boolean('status')->default(0);
            $table->dateTime('verif_payment_at')->nullable();
            $table->foreignId('verif_by_user_id')->nullable();
            $table->char('file', 50)->nullable();
            $table->char('type_payment', 50);
            $table->decimal('price_ppn', 12, 2);
            $table->decimal('price_pph', 12, 2);
            $table->decimal('price_sub', 12, 2);
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
        Schema::dropIfExists('billing_invoices');
    }
}
