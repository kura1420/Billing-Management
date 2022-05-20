<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->enum('gender', ['l', 'p'])->nullable();
            $table->string('email', 100)->unique();
            $table->char('telp', 14)->nullable();
            $table->char('handphone', 14)->nullable();
            $table->char('fax', 50)->nullable();
            $table->text('address');
            $table->char('picture', 50)->default('blank.png');
            $table->date('birthdate')->nullable();
            $table->char('marital_status', 2)->nullable();
            $table->string('work_type', 255)->nullable();
            $table->integer('child', false, false)->default(0);
            $table->foreignUuid('customer_data_id');
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
        Schema::dropIfExists('customer_profiles');
    }
}
