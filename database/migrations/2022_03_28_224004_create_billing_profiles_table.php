<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 50)->unique();
            $table->string('name', 255);
            $table->boolean('active')->default(0);
            $table->text('desc')->nullable();
            $table->foreignUuid('billing_type_id');
            $table->foreignUuid('customer_type_id');
            $table->foreignUuid('customer_segment_id');
            $table->foreignUuid('billing_template_id');
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
        Schema::dropIfExists('billing_profiles');
    }
}
