<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppRoleMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_role_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('app_role_id');
            $table->foreignUuid('app_menu_id');
            $table->boolean('full')->default(0);
            $table->boolean('create')->default(0);
            $table->boolean('read')->default(0);
            $table->boolean('update')->default(0);
            $table->boolean('delete')->default(0);
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
        Schema::dropIfExists('app_role_menus');
    }
}
