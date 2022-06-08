<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouterSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('router_sites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('site', 255);
            $table->boolean('active')->default(0);
            $table->string('host', 100)->unique();
            $table->integer('port', false, false);
            $table->string('user', 100);
            $table->string('password', 100);
            $table->text('desc')->nullable();
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
        Schema::dropIfExists('router_sites');
    }
}
