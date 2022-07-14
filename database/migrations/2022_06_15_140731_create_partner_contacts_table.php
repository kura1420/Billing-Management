<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname', 255);
            $table->string('email', 100)->unique();
            $table->char('handphone', 14)->unique();
            $table->char('telp', 14)->nullable();
            $table->string('position', 255)->nullable();
            $table->foreignUuid('partner_id');
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
        Schema::dropIfExists('partner_contacts');
    }
}
