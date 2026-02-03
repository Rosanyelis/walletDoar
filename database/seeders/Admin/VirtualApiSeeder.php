<?php

namespace Database\Seeders\Admin;

use App\Models\CardyfieCardCustomer;
use App\Models\CardyfieVirtualCard;
use App\Models\VirtualCardApi;
use App\Models\VirtualCardProviderCurrency;
use Illuminate\Database\Seeder;

class VirtualApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $virtual_card_apis = array(
            array('id' => '1','admin_id' => '1','provider_title' => 'Cardyfie','provider_slug' => 'cardyfie','provider_image' => '1c8dc015-7367-417d-90cc-2c147b6d8076.webp','card_details' => '<p>This card is property of Walletium, Wonderland. Misuse is criminal offense. If found, please return to Walletium or to the nearest bank.</p>','config' => '{"id":"1","public_key":"pub_ZB82BBVlOGgeOB9FznQiSYxcuax6zzGAYvuJPCTJbh2nPhu5cmNyAqhQQ8pNSefdmuU6U3iTU67BshrRydHcwlvtaNbgxoFdO47TqwN457DDglo1Qmqjj6m4","secret_key":"sec_Kzy9t5Ax5BzKroOQW7oMVC3xvkCdflrSRuc3NNJY5siJ8gtyurRsF2Gt6eRSS9sXo1Zt9ehm7Xi7lOgxyfaDEH2CYO5YcTpfrQmNhg5ezXg6n9vA92BMjBtD","sandbox_url":"https:\\/\\/core.cardyfie.com\\/api\\/sandbox\\/v1","production_url":"https:\\/\\/core.cardyfie.com\\/api\\/production\\/v1","webhook_secret":"w_sec_9Tm69lrEjXgUt9jGNRnQ5M36p71uGBEmUTYPZ9H47PQU7CgFPbDqW8sokAo6MpnDbkuXymXMl15JXQKKsz4cqpVkJW9KGFi2PkV8Qj4meqeRuz1xhdNXqUQQ","mode":"SANDBOX","name":"cardyfie"}','universal_image' => 'universal.png','platinum_image' => 'platinum.png','supported_currencies' => '["USD"]','status' => '1','created_at' => '2025-10-11 21:37:00','updated_at' => '2025-10-28 10:25:11')
        );

        VirtualCardApi::upsert($virtual_card_apis,['id'],[]);

        $virtual_card_provider_currencies = array(
            array('id' => '1','provider_id' => '1','currency_code' => 'USD','currency_symbol' => '$','image' => NULL,'fees' => '{"cardyfie_universal_card_issues_fee":"3.00000000","cardyfie_platinum_card_issues_fee":"5.00000000","cardyfie_card_deposit_fixed_fee":"1.00000000","cardyfie_card_deposit_percent_fee":"1.00000000","cardyfie_card_withdraw_fixed_fee":"1.00000000","cardyfie_card_withdraw_percent_fee":"1.00000000","cardyfie_card_maintenance_fixed_fee":"0.00000000"}','min_limit' => '5.00000000','max_limit' => '100.00000000','daily_limit' => '1000.00000000','monthly_limit' => '5000.00000000','percent_charge' => '0.00000000','fixed_charge' => '0.00000000','rate' => '1.00000000','status' => '1','created_at' => '2025-10-13 09:38:28','updated_at' => '2025-10-16 16:06:36')

        );

        VirtualCardProviderCurrency::upsert($virtual_card_provider_currencies,['id'],[]);
    }
}
