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
        Schema::create('virtual_card_provider_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('currency_code',20);
            $table->string('currency_symbol')->nullable();
            $table->string('image',255)->nullable();
            $table->longText('fees');
            $table->decimal('min_limit',28,8,true)->unsigned()->default(0);
            $table->decimal('max_limit',28,8,true)->unsigned()->default(0);
            $table->decimal('daily_limit',28,8,true)->unsigned()->default(0);
            $table->decimal('monthly_limit',28,8,true)->unsigned()->default(0);
            $table->decimal('percent_charge',28,8,true)->unsigned()->default(0);
            $table->decimal('fixed_charge',28,8,true)->unsigned()->default(0);
            $table->decimal('rate',28,8,true)->unsigned()->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('virtual_card_apis')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virtual_card_provider_currencies');
    }
};
