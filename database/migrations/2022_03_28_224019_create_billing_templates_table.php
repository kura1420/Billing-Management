<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('sender', ['email', 'sms', 'msgr']);
            $table->text('content');
            $table->enum('type', ['notif', 'suspend', 'terminated', 'closed']);
            $table->foreignUuid('billing_type_id');
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
        Schema::dropIfExists('billing_templates');
    }
}
