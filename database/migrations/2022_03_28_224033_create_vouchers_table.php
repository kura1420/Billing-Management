<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 50)->unique();
            $table->string('password', 255);
            $table->char('type', 20);
            $table->char('bandwidth_speed', 20);
            $table->char('bandwidth_type', 50);
            $table->boolean('status')->default(0);
            $table->dateTime('create_at');
            $table->foreignId('create_by_user_id');
            $table->dateTime('update_at');
            $table->foreignId('update_by_user_id');
            $table->dateTime('active_at');
            $table->dateTime('deactive_at');
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
        Schema::dropIfExists('vouchers');
    }
}
