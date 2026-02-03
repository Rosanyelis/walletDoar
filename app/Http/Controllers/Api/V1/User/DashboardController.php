<?php

namespace App\Http\Controllers\Api\V1\User;

use Carbon\CarbonPeriod;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use App\Models\UserHasInvestPlan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Providers\Admin\CurrencyProvider;

class DashboardController extends Controller
{
    public function dashboard() {
        // User Wallets
        $user_wallets = UserWallet::auth()->with('currency')->get()->map(function($data){
            return[
                'name'                  => $data->currency->name,
                'balance'               => $data->balance,
                'currency_code'         => $data->currency->code,
                'currency_symbol'       => $data->currency->symbol,
                'currency_type'         => $data->currency->type,
                'rate'                  => $data->currency->rate,
                'flag'                  => $data->currency->flag,
                'image_path'            => get_files_public_path('currency-flag'),
            ];
        });
        // Transaction logs
        $transactions = Transaction::with('user','receiver_info')->where(function ($query) {
            $query->where('user_type', GlobalConst::USER)
                  ->where('receiver_id', auth()->user()->id);
        })->orWhere('user_id', auth()->user()->id)
          ->orderByDesc('id')
          ->take(10)
          ->get();
        $transactions = $transactions->transform(function ($item) {
            return [
                'id'               => $item->id,
                'user_id'          => $item->user_id,
                'user_type'        => $item->user_type,
                'type'             => $item->type,
                'attribute'        => $item->attribute,
                'trx_id'           => $item->trx_id,
                'gateway_currency' => $item->gateway_currency->name ?? null,
                'transaction_type' => $item->type,
                'request_amount'   => $item->request_amount,
                'request_currency' => $item->request_currency,
                'exchange_rate'    => $item->exchange_rate,
                'total_charge'     => $item->total_charge,
                'total_payable'    => $item->total_payable,
                'receive_amount'   => $item->receive_amount ?? null,
                'receiver_id'      => $item->receiver_id,
                'payment_currency' => $item->payment_currency,
                'remark'           => $item->remark,
                'status'           => $item->status,
                'created_at'       => $item->created_at,
                'sender_username'  => $item->user->username ?? null,
                'receiver_username'  => $item->receiver_info->username ?? null,
            ];
        });

        // Chart Data
        $monthly_day_list = CarbonPeriod::between(now()->startOfDay()->subDays(30),today()->endOfDay())->toArray();
        $define_day_value = array_fill_keys(array_values($monthly_day_list),"0.00");

        // User Information
        $user_info = auth()->user()->only([
            'id',
            'firstname',
            'lastname',
            'fullname',
            'username',
            'email',
            'image',
            'mobile_code',
            'mobile',
            'full_mobile',
            'email_verified',
            'kyc_verified',
            'two_factor_verified',
            'two_factor_status',
            'two_factor_secret',
        ]);

        $profile_image_paths = [
            'base_url'          => get_asset_url(),
            'path_location'     => files_asset_path_basename("user-profile"),
            'default_image'     => files_asset_path_basename("profile-default"),
        ];
        return Response::success([__('User dashboard data fetch successfully!')],[
            'instructions'  => [
                'transaction_types' => [
                    PaymentGatewayConst::TYPEADDMONEY,
                    PaymentGatewayConst::TYPETRANSFERMONEY,
                    PaymentGatewayConst::TYPEWITHDRAW,
                ],
                'recent_transactions'   => [
                    'status'        => '1: Success, 2: Pending, 3: Hold, 4: Rejected',
                ],
                'user_info'         => [
                    'kyc_verified'  => "0: Default, 1: Approved, 2: Pending, 3:Rejected",
                ]
            ],

            'user_info'     => $user_info,
            'wallets'       => $user_wallets,
            'recent_transactions'   => $transactions,
            'profile_image_paths'   => $profile_image_paths,
        ]);
    }

    public function notifications() {
        $notifications = UserNotification::where('user_id', auth()->user()->id)->latest()->take(5)->get()->map(function($item){
            return[
                'id'      => $item->id,
                'user_id' => $item->user_id,
                'type'    => $item->type,
                'message' => [
                    'title'   => __($item->message->title),
                    'message' => $item->message->message,
                    'image' => $item->message->image,
                    'time'    => $item->created_at->diffForHumans(),
                ],
                'seen'       => $item->seen,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });
        return Response::success([__('User Notification data fetch successfully!')],[
            'notifications'      => $notifications,
        ]);
    }
}
