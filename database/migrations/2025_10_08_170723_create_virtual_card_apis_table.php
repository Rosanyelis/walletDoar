<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_card_apis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("admin_id");
            $table->string("provider_title");
            $table->string("provider_slug");
            $table->string("provider_image");
            $table->text('card_details')->nullable();
            $table->text('config')->nullable();
            $table->text('universal_image')->nullable();
            $table->text('platinum_image')->nullable();
            $table->text('supported_currencies',500)->nullable();
            $table->boolean('status')->default(true);
            $table->integer('card_limit')->default(3);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virtual_card_apis');
    }
};
