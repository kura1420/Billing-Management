<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_profile_id');
            $table->foreignUuid('customer_data_id');
            $table->boolean('active')->default(0);
            $table->string('name', 255);
            $table->enum('gender', ['l', 'p'])->nullable();
            $table->string('position', 255)->nullable();
            $table->char('telp', 14)->nullable();
            $table->char('handphone', 14)->unique();
            $table->string('email', 100)->unique();
            $table->text('address');
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
        Schema::dropIfExists('customer_contacts');
    }
}
