<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 100)->unique();
            $table->string('name', 255);
            $table->text('desc')->nullable();
            $table->boolean('active')->default(0);
            $table->integer('start', false, false)->default(23);
            $table->integer('notif', false, false)->default(7);
            $table->integer('suspend', false, false)->default(3);
            $table->integer('terminated', false, false)->default(1);
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
        Schema::dropIfExists('billing_types');
    }
}
