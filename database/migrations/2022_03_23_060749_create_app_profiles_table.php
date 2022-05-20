<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255)->unique();
            $table->char('telp', 20)->unique();
            $table->string('email', 100)->unique();
            $table->char('fax', 50)->nullable();
            $table->text('address')->nullable();
            $table->char('logo', 50)->default('blank.png');
            $table->string('website', 100)->nullable();
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
        Schema::dropIfExists('app_profiles');
    }
}
