<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 50)->unique();
            $table->string('name', 255);
            $table->char('alias', 100)->nullable();
            $table->string('type', 100);
            $table->char('telp', 14)->nullable();
            $table->string('email', 100)->unique();
            $table->string('fax', 100)->nullable();
            $table->string('handphone', 14)->nullable();
            $table->text('address');
            $table->char('logo', 50)->default('blank.jpg');
            $table->boolean('active');
            $table->dateTime('join');
            $table->dateTime('leave')->nullable();
            $table->foreignUuid('user_profile_id_reff')->nullable()->comment('referensi vendor dari internal');
            $table->foreignUuid('provinsi_id');
            $table->foreignUuid('city_id');
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
        Schema::dropIfExists('partners');
    }
}
