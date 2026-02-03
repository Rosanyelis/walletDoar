<?php

use Illuminate\Support\Facades\DB;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement('ALTER TABLE transactions MODIFY type VARCHAR(255)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $enumValues = [
            'ADD-MONEY',
            'MONEY-OUT',
            'WITHDRAW',
            'COMMISSION',
            'BONUS',
            'TRANSFER-MONEY',
            'MONEY-EXCHANGE',
            'ADD-SUBTRACT-BALANCE',
            'MAKE-PAYMENT',
            'CAPITAL-RETURN',
            "REQUEST-MONEY",
            "REDEEM-VOUCHER",
        ];
        Schema::table('transactions', function (Blueprint $table) use ($enumValues) {
            $table->enum("type", $enumValues)->change();
        });
    }
};
