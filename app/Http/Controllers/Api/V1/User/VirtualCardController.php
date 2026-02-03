<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use Illuminate\Support\Facades\DB;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\CardyFieHelper;
use App\Models\VirtualCardApi;
use App\Models\UserNotification;
use App\Constants\PaymentGatewayConst;
use App\Models\CardyfieVirtualCard;
use App\Models\CardyfieCardCustomer;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\CardFundRequest;
use App\Http\Requests\User\CardIssueRequest;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Requests\User\CardCustomerRequest;
use App\Notifications\User\CardIssueNotification;
use App\Notifications\User\CardDepositNotification;
use App\Notifications\User\CardWithdrawNotification;

class VirtualCardController extends Controller
{
    protected $api;
    protected $basic_settings;

    public function __construct()
    {
        $this->api            = VirtualCardApi::first();
        $this->basic_settings = BasicSettingsProvider::get();
    }

    /**
     * api for card statistics
     */

    public function statistics()
    {
        $user            = auth()->user();
        $card_basic_info = [
            'card_back_details' => $this->api->card_details,
            'card_universal'    => get_image($this->api->universal_image ?? null, 'card-api'),
            'card_platinum'     => get_image($this->api->platinum_image ?? null, 'card-api'),
            'site_title'        => $this->basic_settings->site_name,
            'site_logo'         => get_logo($this->basic_settings),
            'site_fav'          => get_fav($this->basic_settings),
        ];

        if ($this->api->config->mode == GlobalConst::SANDBOX) {
            $customer_cards = CardyfieVirtualCard::auth()->where('env', strtoupper(GlobalConst::SANDBOX))->latest()->get();
        } else {
            $customer_cards = CardyfieVirtualCard::auth()->whereNot('env', strtoupper(GlobalConst::SANDBOX))->latest()->get();
        }

        $customer_cards = $customer_cards->map(function ($data) {
            $statusInfo = [
                "PROCESSING" => "PROCESSING",
                "ENABLED"    => "ENABLED",
                "FREEZE"     => "FREEZE",
                "CLOSED"     => "CLOSED",
            ];
            return [
                'id'            => $data->id,
                'reference_id'  => $data->reference_id,
                'ulid'          => $data->ulid,
                'customer_ulid' => $data->customer_ulid,
                'card_name'     => $data->card_name,
                'amount'        => floatval($data->amount ?? 0),
                'currency'      => $data->currency,
                'card_type'     => $data->card_type ?? '',
                'card_provider' => $data->card_tier ?? '',
                'card_exp_time' => $data->card_exp_time,
                'masked_pan'    => $data->masked_pan,
                'address'       => $data->address,
                'status'        => $data->status,
                'env'           => $data->env,
                'status_info'   => (object)$statusInfo,
            ];
        });

        $transactions = Transaction::auth()->virtualCard()->latest()->get()->map(function ($item) {
            $statusInfo = [
                "success"  => 1,
                "pending"  => 2,
                "rejected" => 3,
            ];
            $card_currency = $item->details->card_info->currency ?? get_default_currency_code();

            return [
                'id'               => $item->id,
                'trx'              => $item->trx_id,
                'transaction_type' => $item->type,
                'request_amount'   => get_amount($item->request_amount, null,),
                'request_currency' => $item->request_currency,
                'exchange_rate'    => get_amount($item->exchange_rate, null, 3),
                'fixed_charge'     => get_amount($item->fixed_charge, null, 3),
                'percent_charge'   => get_amount($item->percent_charge, null, 3),
                'total_charge'     => get_amount($item->total_charge, null, 3),
                'total_payable'    => get_amount($item->total_payable, null, 3),
                'receive_amount'   => get_amount($item->receive_amount, null, 3),
                'payment_currency' => $item->payment_currency,
                'card_amount'      => get_amount(@$item->details->card_info->amount, $card_currency, 3),
                'card_number'      => $item->details->card_info->card_pan ?? $item->details->card_info->maskedPan ?? $item->details->card_info->card_number ?? "",
                'current_balance'  => get_amount($item->available_balance, get_default_currency_code(), 3),
                'status'           => $item->stringStatus->value,
                'date_time'        => $item->created_at,
                'status_info'      => (object)$statusInfo,

            ];
        });

        $card_customer = $user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first();

        $total_cards = CardyfieVirtualCard::auth()->where('env', $this->api->config->mode)->count() ?? 0;

        $total_freezed_cards   = CardyfieVirtualCard::auth()->where('env', $this->api->config->mode)->where('status', CardyfieVirtualCard::CARD_FREEZE_STATUS)->count() ?? 0;
        $total_enabled_cards   = CardyfieVirtualCard::auth()->where('env', $this->api->config->mode)->where('status', CardyfieVirtualCard::CARD_ENABLED_STATUS)->count() ?? 0;
        $total_amount_of_cards = CardyfieVirtualCard::auth()->where('env', $this->api->config->mode)->selectRaw('currency, SUM(amount) as total_amount')->groupBy('currency')->first();
        if ($total_amount_of_cards == null) {
            $total_amount_of_cards = (object) [
                'total_amount' => 0.0,
                'currency'     => '',
            ];
        }
        $supported_currency    = $this->api->currencies;
        $wallet                = UserWallet::auth()->with(['currency'])
            ->whereHas('currency', function ($query) {
                $query->active();
            })
            ->active()
            ->get();

        $data = [
            'total_cards'            => $total_cards,
            'total_freezed_cards'    => $total_freezed_cards,
            'total_enabled_cards'    => $total_enabled_cards,
            'total_amount_of_cards'  => $total_amount_of_cards,
            'supported_currency'     => $supported_currency,
            'customer_create_status' => $card_customer == null ? true : false,
            'card_basic_info'        => (object) $card_basic_info,
            'customer_cards'         => $customer_cards,
            'user'                   => $user,
            'user_wallet'            => (object)$wallet,
            'transactions'           => $transactions,
        ];

        return Response::success([__('Virtual card info fetched successfully')], $data);
    }

    /**
     *  customer info api
     */
    public function customerInfo()
    {
        $user                  = auth()->user();
        $customer_exist_status = $user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first() != null ? true : false;
        $card_create_status    = ($user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first()?->status == CardyfieCardCustomer::STATUS_APPROVED)  ? true : false;

        if ($customer_exist_status) {
            $customer_exist = $user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first();
        } else {
            $customer_exist = [
                "id"             => "",
                "user_type"      => "",
                "user_id"        => "",
                "ulid"           => "",
                "reference_id"   => "",
                "first_name"     => "",
                "last_name"      => "",
                "email"          => "",
                "date_of_birth"  => "",
                "id_type"        => "",
                "id_number"      => "",
                "id_front_image" => "",
                "id_back_image"  => "",
                "user_image"     => "",
                "house_number"   => "",
                "address_line_1" => "",
                "city"           => "",
                "state"          => "",
                "zip_code"       => "",
                "country"        => "",
                "status"         => "",
                "meta"           => "",
                "env"            => "",
                "created_at"     => "",
                "updated_at"     => ""
            ];
            $customer_exist = (object) $customer_exist;
        }

        $customer_pending_text = __("Please wait until your customer status is APPROVED. Once it is APPROVED, you can continue with the card creation.");

        $data = [
            'user'                  => $user,
            'customer_exist_status' => $customer_exist_status,
            'customer_pending_text' => $customer_pending_text,
            'customer_exist'        => $customer_exist,
            'card_create_status'    => $card_create_status,
        ];

        return Response::success([__('Virtual card customer info fetched successfully.')], $data);
    }

    /**
     * create customer
     */
    public function createCustomer(CardCustomerRequest $request)
    {
        $validated     = $request->validated();
        $user          = auth()->user();
        $card_customer = $user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first();

        try {
            if ($card_customer == null) {
                if ($request->hasFile('id_front_image')) {
                    $image_data                  = saveImageAndGetUrl($request->file('id_front_image'), 'card-kyc-images');
                    $validated['id_front_image'] = $image_data['url'];
                    $uploaded_images[]           = $image_data['path'];
                }

                if ($request->hasFile('id_back_image')) {
                    $image_data                 = saveImageAndGetUrl($request->file('id_back_image'), 'card-kyc-images');
                    $validated['id_back_image'] = $image_data['url'];
                    $uploaded_images[]          = $image_data['path'];
                }

                // user image
                if ($request->hasFile('user_image')) {
                    $image_data              = saveImageAndGetUrl($request->file('user_image'), 'card-kyc-images');
                    $validated['user_image'] = $image_data['url'];
                    $uploaded_images[]       = $image_data['path'];
                }
                $validated['reference_id'] = 'ref-' . getTrxNum();
                $validated['user_type']    = 'USER';
                $validated['user_id']      = $user->id;
                $validated['status']       = CardyfieCardCustomer::STATUS_PENDING;
                $validated['env']          = $this->api->config->mode;
                $stored_data               = CardyfieCardCustomer::create($validated);
                $create_customer           = (new CardyFieHelper())->createCustomer($validated);

                if ($create_customer['status'] == false) {
                    foreach ($uploaded_images as $file_path) {
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                    $stored_data->delete();
                    return $this->apiErrorHandle($create_customer['message']);
                }
                $stored_data->ulid   = $create_customer['data']['customer']['ulid'];
                $stored_data->meta   = $create_customer['data']['customer']['meta'];
                $stored_data->status = $create_customer['data']['customer']['status'];
                $stored_data->save();
                return Response::success([$createCustomer['message'][0] ?? __('Customer has been created successfully.')]);
            } else {
                return Response::error([__("Customer already exist. You can not create more than one customer.")]);
            }
        } catch (Exception $e) {
            return Response::error([__("Something went wrong! Please try again.")]);
        }
    }

    /**
     * customer edit info
     */
    public function editCustomerInfo()
    {
        $user          = auth()->user();
        $card_customer = $user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first();

        if (!$card_customer) {;
            Response::error([__("Customer not found.")], null);
        }

        $get_customer = (new CardyFieHelper())->getCustomer((string) $card_customer->ulid);

        if ($get_customer['status'] == false) {
            return $this->apiErrorHandle($get_customer['message']);
        }

        $data = [
            'card_customer' => $card_customer,
        ];

        return Response::success([__('Data fetched successful.')], $data);
    }

    /**
     * update customer information
     */
    public function updateCustomer(CardCustomerRequest $request)
    {
        $validated     = $request->validated();
        $user          = auth()->user();
        $card_customer = $user->cardyfieCardCustomer()->where('env', $this->api->config->mode)->first();

        if (!$card_customer) {
            return Response::error([__("Customer not found.")]);
        }
        $uploaded_images = [];
        try {
            if ($request->hasFile('id_front_image')) {
                $image_data                  = saveImageAndGetUrl($request->file('id_front_image'), 'card-kyc-images');
                $validated['id_front_image'] = $image_data['url'];
                $uploaded_images[]           = $image_data['path'];
            } else {
                $validated['id_front_image'] = $card_customer->id_front_image;
            }

            // id back image
            if ($request->hasFile('id_back_image')) {
                $image_data                 = saveImageAndGetUrl($request->file('id_back_image'), 'card-kyc-images');
                $validated['id_back_image'] = $image_data['url'];
                $uploaded_images[]          = $image_data['path'];
            } else {
                $validated['id_back_image'] = $card_customer->id_back_image;
            }

            // user image
            if ($request->hasFile('user_image')) {
                $image_data              = saveImageAndGetUrl($request->file('user_image'), 'card-kyc-images');
                $validated['user_image'] = $image_data['url'];
                $uploaded_images[]       = $image_data['path'];
            } else {
                $validated['user_image'] = $card_customer->user_image;
            }

            $update_customer = (new CardyFieHelper())->updateCustomer($validated, (string) $card_customer->ulid);
            
            if ($update_customer['status'] == false) {
                
                foreach ($uploaded_images as $file_path) {
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                
                return $this->apiErrorHandle($update_customer['message']);
            }
            
            try {
                if ($card_customer) {
                    $customer_data = $update_customer['data']['customer'];
                    $card_customer->update(
                        array_merge(
                            [
                                'user_type' => 'USER',
                                'user_id'   => $user->id,
                            ],
                            $customer_data,
                        ),
                    );
                }
            } catch (Exception $e) {
                return Response::error([__("Something went wrong! Please try again.")]);
            }
            return Response::success([__('Customer has been updated successfully.')]);
        } catch (Exception $e) {
            return Response::error([__("Something went wrong! Please try again.")]);
        }
    }

    /**
     *  card issue
     */
    public function cardIssue(CardIssueRequest $request)
    {
        $validated                = $request->validated();
        $validated['card_name']   = ucwords(strtolower($validated['card_name']));
        $validated['card_amount'] = 0;
        $user                     = auth()->user();
        $wallet                   = UserWallet::auth()->whereHas('currency', function ($q) use ($validated) {
            $q->where('code', $validated['from_currency'])->active();
        })
            ->active()
            ->first();

        if (!$wallet) return Response::error([__('User wallet not found')], null);

        $card_currency = $this->api->currencies->first();
        $card_charge   = $this->api->currencies->first()->fees;

        if (!$card_currency) return Response::error([__('Card Currency Not Found')], null);

        $charges = $this->cardIssueCharge($validated['card_type'], $validated['card_amount'], $card_charge, $wallet, $card_currency);

        if ($charges['payable'] > $wallet->balance) return Response::error([__('Your Wallet Balance Is Insufficient')], null);

        $customer = $user->cardyfieCardCustomer;

        if (!$customer) return Response::error([__("The customer does not create properly, Please contact with administrator.")], null);

        $validated['customer_ulid'] = $customer->ulid;
        $validated['reference_id']  = 'ref-' . getTrxNum();

        DB::beginTransaction();
        try {
            $card_id = DB::table('cardyfie_virtual_cards')->insertGetId([
                'user_type'     => 'USER',
                'user_id'       => $user->id,
                'reference_id'  => $validated['reference_id'],
                'customer_ulid' => $validated['customer_ulid'],
                'card_name'     => $validated['card_name'],
                'amount'        => $validated['card_amount'],
                'currency'      => $validated['card_currency'],
                'card_tier' => $validated['card_provider'],
                'card_type'     => $validated['card_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $issue_card = (new CardyFieHelper())->issueCard($validated);

            if ($issue_card['status'] == false) return $this->apiErrorHandle($issue_card['message']);

            $card                = CardyfieVirtualCard::where('id', $card_id)->first();
            $card->ulid          = $issue_card['data']['virtual_card']['ulid'] ?? null;
            $card->amount        = $issue_card['data']['virtual_card']['card_balance'] ?? floatval(0);
            $card->card_exp_time = $issue_card['data']['virtual_card']['card_exp_time'] ?? null;
            $card->masked_pan    = $issue_card['data']['virtual_card']['masked_pan'] ?? null;
            $card->address       = $issue_card['data']['virtual_card']['address'] ?? null;
            $card->status        = $issue_card['data']['virtual_card']['status'] ?? null;
            $card->env           = $issue_card['data']['virtual_card']['env'] ?? null;
            $card->save();
 
            $trx_id = 'CI' . getTrxNum();
            $this->insertCardIssue($trx_id, $user, $wallet, $card, $charges);
            $this->handleCardIssueNotification($trx_id, $charges, $user, $card);
            DB::commit();
            return Response::success([__('Card issued successfully')], ['card' => $card]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error([__('Something went wrong! Please try again.')]);
        }
    }

    /**
     * card issue charge calculation
     */
    public function cardIssueCharge($card_type, $card_amount, $card_charge, $wallet, $card_currency)
    {
        $precision = 8;

        if ($card_type == VirtualCardApi::TYPE_UNIVERSAL) {
            $card_issues_fixed_fee = $card_charge->cardyfie_universal_card_issues_fee;
        } else {
            $card_issues_fixed_fee = $card_charge->cardyfie_platinum_card_issues_fee;
        }

        $exchange_rate = $wallet->currency->rate / $card_currency->rate;

        $data['exchange_rate']       = $exchange_rate;
        $data['card_amount']         = $card_amount;
        $data['card_currency']       = $card_currency->currency_code;
        $data['card_currency_rate']  = $card_currency->rate;
        $data['from_amount']         = $data['card_amount'] * $exchange_rate;
        $data['from_currency']       = $wallet->currency->code;
        $data['from_currency_rate']  = $wallet->currency->rate;
        $data['issue_charge']        = $card_issues_fixed_fee;
        $data['issue_charge_calc']   = $card_issues_fixed_fee * $exchange_rate;
        $data['fixed_charge']        = $data['issue_charge_calc'];
        $data['total_charge']        = $data['issue_charge_calc'];
        $data['from_wallet_balance'] = $wallet->balance;
        $data['payable']             = $data['from_amount'] + $data['total_charge'];
        $data['precision_digit']     = $precision;

        return $data;
    }

    /*
    *card details info
    */
    public function cardDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_ulid' => 'required|exists:cardyfie_virtual_cards,ulid',
        ]);

        if ($validator->fails()) {
            return Response::validation($validator->errors()->all(), null);
        }

        $validated = $validator->validate();
        $card      = CardyfieVirtualCard::auth()->where('ulid', $validated['card_ulid'])->first();

        if (!$card) return Response::error([__('Card does not exist')]);

        try {

            $get_card = (new CardyFieHelper())->getCard((string) $card->ulid);

            if ($get_card['status'] == false) return Response::error([__($get_card['message'])], null);

            $card_details = $get_card['data']['virtual_card'];
            $card         = [
                "id"            => $card->id,
                "reference_id"  => $card->reference_id,
                "ulid"          => $card->ulid,
                "customer_ulid" => $card->customer_ulid,
                "card_name"     => $card->card_name,
                "amount"        => $card->amount,
                "currency"      => $card->currency,
                "card_type"     => $card->card_type,
                "card_provider" => $card->card_provider,
                "card_exp_time" => $card->card_exp_time,
                "masked_pan"    => $card->masked_pan,
                "real_pan"      => $card_details['real_pan'] ?? $card->masked_pan,
                "cvv"           => $card_details['cvv'] ?? "***",
                "address"       => $card->address,
                "status"        => $card->status,
                "env"           => $card->env,
                "created_at"    => $card->created_at,
                "updated_at"    => $card->updated_at,
            ];
            return Response::success(
                [__('Card details fetched successfully')],
                [
                    'card' => $card,
                ]
            );
        } catch (Exception $e) {
            return Response::error([__('Something went wrong! Please try again.')], null, 500);
        }
    }

    /**
     * card deposit
     */
    public function cardDeposit(CardFundRequest $request)
    {
        $validated = $request->validated();
        $user      = auth()->user();
        $card      = CardyfieVirtualCard::auth()->where('id', $validated['id'])->first();

        if (!$card)  return Response::error([__('Card does not exist')]);

        if ($card->status === CardyfieVirtualCard::CARD_FREEZE_STATUS)
            return Response::error([__('This card is frozen. Please unfreeze it before making a deposit.')]);

        if ($card->status === CardyfieVirtualCard::CARD_CLOSED_STATUS) {
            return Response::error([__('This card has been closed. You cannot perform any transactions.')]);
        }
        
        $wallet = UserWallet::where('user_id', $user->id)
            ->whereHas('currency', function ($q) use ($validated) {
                $q->where('code', $validated['from_currency'])->active();
            })
            ->active()
            ->first();

        if (!$wallet) {
            return Response::error(['error' => [__('User wallet not found')]], null);
        }
        if (!$wallet)  return Response::error([__('User wallet not found')]);

        $card_currency = $this->api->currencies->where('status', true)->where('currency_code', $validated['card_currency'])->first();
        $card_charge   = $card_currency->fees;

        if (!$card_currency) return Response::error([__('Card currency not found')]);

        $charges   = $this->cardDepositCharges($validated['amount'], $card_charge, $wallet, $card_currency);
        $min_limit = $card_currency->min_limit;
        $max_limit = $card_currency->max_limit;

        if ($validated['amount'] < $min_limit || $validated['amount'] > $max_limit) {
            return Response::error([__("Please follow the transaction limit")]);
        }

        if ($charges['payable'] > $wallet->balance) {
            return Response::error([__("Your wallet balance is insufficient")], null);
        }

        try {
            $deposit_request = (new CardyFieHelper())->depositCard((string) $card->ulid, $validated['amount']);

            if ($deposit_request['status'] == false) {
                return $this->apiErrorHandle($deposit_request['message']);
            }

            $card->amount = $deposit_request['data']['virtual_card']['card_balance'];
            $card->save();

            $trx_id = 'CD' . getTrxNum();
            $this->insertCardDeposit($trx_id, $user, $wallet, $validated['amount'], $card, $charges);
            $this->handleCardDepositNotification($trx_id, $charges, $user, $card, $validated['amount']);
            DB::commit();
            return Response::success([__('Card deposit successful.')]);
        } catch (Exception $e) {
            return Response::error([__("Something went wrong! Please try again.")]);
        }
    }

    /**
     * card withdraw submit
     */
    public function cardWithdraw(CardFundRequest $request)
    {
        $validated = $request->validated();
        $card      = CardyfieVirtualCard::auth()->where('id', $validated['id'])->first();
        $user      = auth()->user();

        if (!$card) return Response::error([__('Card does not exist')]);

        if ($card->status === CardyfieVirtualCard::CARD_FREEZE_STATUS)
            return Response::error([__('This card is frozen. Please unfreeze it before making a withdraw.')]);

        if ($card->status === CardyfieVirtualCard::CARD_CLOSED_STATUS) {
            return Response::error([__('This card has been closed. You cannot perform any transactions.')]);
        }

         $wallet = UserWallet::where('user_id', $user->id)
            ->whereHas('currency', function ($q) use ($validated) {
                $q->where('code', $validated['from_currency'])->active();
            })
            ->active()
            ->first();

        if (!$wallet) {
            return Response::error(['error' => [__('User wallet not found')]], null);
        }

        if (!$wallet) return Response::error([__('User wallet not found')]);

        $card_currency = $this->api->currencies->where('status', true)->where('currency_code', $validated['card_currency'])->first();
        $card_charge   = $card_currency->fees;

        if (!$card_currency) return Response::error([__('Card currency not found')]);

        $get_card = (new CardyFieHelper())->getCard((string) $card->ulid);

        if ($get_card['status'] == false) {
            return $this->apiErrorHandle($get_card['message']);
        }

        $charges = $this->cardWithdrawCharges($validated['amount'], $card_charge, $wallet, $card_currency);

        if ($charges['payable'] > $get_card['data']['virtual_card']['card_balance']) {
            return Response::error([__('Your card does not have enough funds to complete this transaction')]);
        }


        $min_limit = $card_currency->min_limit;
        $max_limit = $card_currency->max_limit;

        if ($validated['amount'] < $min_limit || $validated['amount'] > $max_limit) {
            return Response::error([__('Please follow the transaction limit')]);
        }

        try {
            $withdraw_request = (new CardyFieHelper())->withdrawCard((string) $card->ulid, $charges['payable']);

            if ($withdraw_request['status'] == false) {
                return $this->apiErrorHandle($withdraw_request['message']);
            }

            $card->amount -= $charges['payable'];
            $card->save();
            $trx_id = 'CW' . getTrxNum();
            $this->insertCardWithdraw($trx_id, $user, $wallet, $validated['amount'], $card, $charges);
            $this->handleCardWithdrawNotification($trx_id, $charges, $user, $card, $validated['amount']);
            DB::commit();
            return Response::success([__('Card withdraw successful.')]);
        } catch (Exception $e) {
            return Response::error([__("Something went wrong! Please try again.")],);
        }
    }

    /**
     * send email and internal notification on card withdraw event
     */
    public function handleCardWithdrawNotification($trx_id, $charges, $user, $card, $amount)
    {

        DB::beginTransaction();
        try {
            $notification_content = [
                'title'   => __('Card Withdraw'),
                'message' => __('Card withdraw successful') . ' ' . $card->masked_pan ?? '---- ---- ---- ----' . 'with ' . get_amount($amount, $charges['card_currency'], $charges['precision_digit']),
                'image'   => files_asset_path('user-profile'),
            ];


            UserNotification::create([
                'type'    => NotificationConst::CARD_WITHDRAW,
                'user_id' => $user->id,
                'message' => $notification_content,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }
        $basic_settings = $this->basic_settings;
        if ($basic_settings->email_notification) {
            try {

                $mail_data =  [
                    'trx_id'         => $trx_id,
                    'title'          => 'Card Withdraw',
                    'request_amount' => get_amount($amount, $charges['card_currency'], $charges['precision_digit']),
                    'payable'        => get_amount($charges['payable'], $charges['card_currency'], $charges['precision_digit']),
                    'charges'        => get_amount($charges['total_charge'], $charges['card_currency'], $charges['precision_digit']),
                    'receive_amount' => get_amount($charges['receive_amount'], $charges['from_currency'], $charges['precision_digit']),
                    'card_pan'       => $card->masked_pan ?? '---- ----- ---- ----',
                    'status'         => $card->status ?? '',
                ];
                $user->notify(new CardWithdrawNotification((object) $mail_data));
            } catch (Exception $e) {
            }
        }
    }

    /**
     * change the card status to freeze/unfreeze
     */
    public function freezeUnfreeze(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|string',
            'card_ulid' => 'required|exists:cardyfie_virtual_cards,ulid',
        ]);

        if ($validator->fails()) {
            return Response::validation($validator->errors()->all(), null);
        }

        $validated = $validator->validated();
        $card      = CardyfieVirtualCard::auth()->where('ulid', $validated['card_ulid'])->first();

        if (!$card) {
            return Response::error([__('Card not found.')]);
        }

        if ($card->status == 'CLOSED') {
            return Response::error([__('This card is permanently closed and cannot be changed.')]);
        }

        $get_card = (new CardyFieHelper())->getCard((string)$card->ulid);

        if ($get_card['status'] == false) {
            return $this->apiErrorHandle($get_card["message"]);
        }

        if ($validated['status'] == CardyfieVirtualCard::CARD_ENABLED_STATUS) {
            $make_unfreeze = (new CardyFieHelper())->unfreeze((string)$card->ulid);
            if ($make_unfreeze['status'] == false) {
                return $this->apiErrorHandle($make_unfreeze["message"]);
            }
            $card->status = CardyfieVirtualCard::CARD_ENABLED_STATUS;
            $card->save();

            return Response::success([__('Card unfreeze successfully!')], null);
        } elseif ($validated['status'] == CardyfieVirtualCard::CARD_FREEZE_STATUS) {
            $freeze = (new CardyFieHelper())->freeze((string)$card->ulid);
            if ($freeze['status'] == false) {
                return $this->apiErrorHandle($freeze["message"]);
            }

            $card->status = CardyfieVirtualCard::CARD_FREEZE_STATUS;
            $card->save();



            return Response::success([__('Card frozen successfully!')], null);
        }
    }

    /**
     * close the card
     */
    public function closeCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_ulid' => 'required|exists:cardyfie_virtual_cards,ulid',
        ]);

        if ($validator->fails()) {
            return Response::validation($validator->errors()->all(), null);
        }

        $validated = $validator->validate();
        $card      = CardyfieVirtualCard::auth()->where('ulid', $validated['card_ulid'])->first();

        if (!$card) {
            return Response::error([__('Card not found.')]);
        }

        if ($card->status == 'CLOSED') {
            return Response::error([__('This card is already closed.')]);
        }

        $get_card = (new CardyFieHelper())->getCard((string)$card->ulid);

        if ($get_card['status'] == false) {
            return $this->apiErrorHandle($get_card["message"]);
        }

        $make_close = (new CardyFieHelper())->closeCard((string)$card->ulid);

        if ($make_close['status'] == false) {
            return $this->apiErrorHandle($make_close["message"]);
        }

        try {
            $card->update([
                'status' => CardyfieVirtualCard::CARD_CLOSED_STATUS,
            ]);
        } catch (Exception $e) {
            return Response::error([__("Something went wrong! Please try again.")], null);
        }

        return Response::success([__('Card closed successfully')], null);
    }

    /**
     * card transactions list
     */
    public function cardTransaction()
    {
        $validator = Validator::make(request()->all(), [
            'card_ulid' => 'required|exists:cardyfie_virtual_cards,ulid',
        ]);
        if ($validator->fails()) {
            return Response::validation($validator->errors()->all(), null);
        }

        $validated = $validator->validated();
        $card      = CardyfieVirtualCard::auth()->where('ulid', $validated['card_ulid'])->first();

        if (!$card) {
            return Response::error([__('Card not found.')]);
        }

        $get_card_transactions = (new CardyFieHelper())->getCardTransaction((string) $card->ulid);

        if ($get_card_transactions['status'] == false) {
            return $this->apiErrorHandle($get_card_transactions["message"]);
        }

        $get_card_transactions = $get_card_transactions['data']['transactions'] ?? [];

        $data = [
            'card_transactions' => $get_card_transactions ?? [],
        ];

        return Response::success([__('Virtual card transaction fetched successfully.')], $data);
    }

    /**
     * insert card withdraw data in transactions
     */
    public function insertCardWithdraw($trx_id, $user, $wallet, $amount, $card, $charges)
    {
        $update_balance = $wallet->balance + $charges['receive_amount'];
        $details        = [
            'card_info' => $card ?? '',
            'charges'   => $charges,
        ];
        DB::beginTransaction();
        try {
            DB::table('transactions')->insert([
                'type' => PaymentGatewayConst::VIRTUALCARD,
                'trx_id' => $trx_id,
                'user_type'      => GlobalConst::USER,
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'payment_gateway_currency_id' => null,
                'request_amount' => $amount,
                'request_currency' => $charges['card_currency'],
                'exchange_rate'      => $charges['exchange_rate'],
                'percent_charge' => $charges['percent_charge'],
                'fixed_charge' => $charges['fixed_charge'],
                'total_charge' => $charges['total_charge'],
                'total_payable' => $amount,
                'receive_amount' => $charges['payable'],
                'receiver_type'                 => GlobalConst::USER,
                'receiver_id'                   => $user->id,
                'available_balance' => $update_balance,
                'payment_currency' => $charges['from_currency'],
                'remark' => ucwords(PaymentGatewayConst::CARDWITHDRAW),
                'details' => json_encode($details),
                'status' => GlobalConst::STATUS_CONFIRM_PAYMENT,
                'created_at' => now(),
            ]);
            $this->updateWalletBalance($wallet, $update_balance);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }
    }

    /**
     * card withdraw charges calculation
     */
    public function cardWithdrawCharges($amount, $card_charges, $wallet, $card_currency)
    {
        $precision                   = 8;
        $exchange_rate               = $wallet->currency->rate / $card_currency->rate;
        $data['exchange_rate']       = $exchange_rate;
        $data['card_amount']         = $amount;
        $data['card_currency']       = $card_currency->currency_code;
        $data['card_currency_rate']  = $card_currency->rate;
        $data['receive_amount']      = $amount * $exchange_rate;
        $data['from_currency']       = $wallet->currency->code;
        $data['from_currency_rate']  = $wallet->currency->rate;
        $data['fixed_charge']        = $card_charges->cardyfie_card_withdraw_fixed_fee;
        $data['percent_charge']      = $card_charges->cardyfie_card_withdraw_percent_fee / 100;
        $data['total_charge']        = $data['fixed_charge'] + $data['percent_charge'];
        $data['from_wallet_balance'] = $wallet->balance;
        $data['payable']             = $amount + $data['total_charge'];
        $data['precision_digit']     = $precision;

        return $data;
    }

    /**
     * insert card issue data to transaction table
     */
    public function insertCardIssue($trx_id, $user, $wallet, $card, $charges)
    {
        $update_balance = $wallet->balance - $charges['payable'];
        $details        = [
            'card_info' => $card ?? '',
            'charges'   => $charges
        ];
        DB::beginTransaction();

        try {
            DB::table('transactions')->insert([
                'type' => PaymentGatewayConst::VIRTUALCARD,
                'trx_id' => $trx_id,
                'user_type'      => GlobalConst::USER,
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'payment_gateway_currency_id' => null,
                'request_amount' => $charges['card_amount'],
                'request_currency' => $charges['card_currency'],
                'exchange_rate'      => $charges['exchange_rate'],
                'percent_charge' => 0,
                'fixed_charge' => $charges['fixed_charge'],
                'total_charge' => $charges['total_charge'],
                'total_payable' => $charges['payable'],
                'receive_amount'                => 0,
                'receiver_type'                 => GlobalConst::USER,
                'receiver_id'                   => $user->id,
                'available_balance' => $update_balance,
                'payment_currency' => $charges['from_currency'],
                'remark' => PaymentGatewayConst::CARDBUY,
                'details' => json_encode($details),
                'status' => GlobalConst::STATUS_CONFIRM_PAYMENT,
                'created_at' => now(),
            ]);
            $this->updateWalletBalance($wallet, $update_balance);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }
    }
    /**
     * send email and internal notification on card issue event
     */
    public function handleCardIssueNotification($trx_id, $charges, $user, $card)
    {
        DB::beginTransaction();
        try {
            $notification_content = [
                'title'   => __('Card Issue'),
                'message' => __('Card issue successful') . ' ' . $card->masked_pan ?? '---- ---- ---- ----',
                'image'   => files_asset_path('user-profile'),
            ];

            UserNotification::create([
                'type'    => NotificationConst::CARD_BUY,
                'user_id' => $user->id,
                'message' => $notification_content,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }
        $basic_settings = $this->basic_settings;
        if ($basic_settings->email_notification) {
            try {
                $mail_data =  [
                    'trx_id'         => $trx_id,
                    'title'          => 'Card Issue',
                    'request_amount' => get_amount($charges['issue_charge'], $charges['card_currency']),
                    'payable'        => get_amount($charges['payable'], $charges['from_currency']),
                    'charges'        => get_amount($charges['total_charge'], $charges['from_currency']),
                    'card_amount'    => get_amount($card->amount, $charges['card_currency']),
                    'card_pan'       => $card->masked_pan ?? '---- ----- ---- ----',
                    'status'         => $card->status ?? '',
                ];
                $user->notify(new CardIssueNotification((object) $mail_data));
            } catch (Exception $e) {
            }
        }
    }

    /**
     * update balance after transaction
     */
    public function updateWalletBalance($wallet, $update_balance)
    {
        $wallet->update([
            'balance' => $update_balance
        ]);
    }
    /**
     * insert card deposit data in transactions
     */
    public function insertCardDeposit($trx_id, $user, $wallet, $amount, $card, $charges)
    {
        $update_balance = $wallet->balance - $charges['payable'];
        $details        = [
            'card_info' => $card ?? '',
            'charges'   => $charges,
        ];
        DB::beginTransaction();
        try {
            DB::table('transactions')->insert([
                'type' => PaymentGatewayConst::VIRTUALCARD,
                'trx_id' => $trx_id,
                'user_type'      => GlobalConst::USER,
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'payment_gateway_currency_id' => null,
                'request_amount' => $amount,
                'request_currency' => $charges['card_currency'],
                'exchange_rate'      => $charges['exchange_rate'],
                'percent_charge' => $charges['percent_charge'],
                'fixed_charge' => $charges['fixed_charge'],
                'total_charge' => $charges['total_charge'],
                'total_payable' => $charges['payable'],
                'receive_amount'                => 0,
                'receiver_type'                 => GlobalConst::USER,
                'receiver_id'                   => $user->id,
                'available_balance' => $update_balance,
                'payment_currency' => $charges['from_currency'],
                'remark' => ucwords(PaymentGatewayConst::CARDFUND),
                'details' => json_encode($details),
                'status' => GlobalConst::STATUS_CONFIRM_PAYMENT,
                'created_at' => now(),
            ]);
            $this->updateWalletBalance($wallet, $update_balance);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }
    }
    /**
     * card deposit charges calculation
     */
    public function cardDepositCharges($amount, $card_charges, $wallet, $card_currency)
    {
        $precision     = 8;
        $exchange_rate = $wallet->currency->rate / $card_currency->rate;

        $data['exchange_rate']       = $exchange_rate;
        $data['card_amount']         = $amount;
        $data['card_currency']       = $card_currency->currency_code;
        $data['card_currency_rate']  = $card_currency->rate;
        $data['from_amount']         = $amount * $exchange_rate;
        $data['from_currency']       = $wallet->currency->code;
        $data['from_currency_rate']  = $wallet->currency->rate;
        $data['fixed_charge']        = $card_charges->cardyfie_card_deposit_fixed_fee * $exchange_rate;
        $data['percent_charge']      = ($card_charges->cardyfie_card_deposit_percent_fee / 100) * $exchange_rate;
        $data['total_charge']        = $data['fixed_charge'] + $data['percent_charge'];
        $data['from_wallet_balance'] = $wallet->balance;
        $data['payable']             = $data['from_amount'] + $data['total_charge'];
        $data['precision_digit']     = $precision;
        return $data;
    }


    /**
     * send email and internal notification on card deposit event
     */
    public function handleCardDepositNotification($trx_id, $charges, $user, $card, $amount)
    {
        DB::beginTransaction();
        try {
            $notification_content = [
                'title'   => __('Card Deposit'),
                'message' => __('Card deposit successful') . ' ' . $card->masked_pan ?? '---- ---- ---- ----' . 'with ' . get_amount($amount, $charges['card_currency'], $charges['precision_digit']),
                'image'   => files_asset_path('user-profile'),
            ];


            UserNotification::create([
                'type'    => NotificationConst::CARD_FUND,
                'user_id' => $user->id,
                'message' => $notification_content,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception(__('Something went wrong! Please try again.'));
        }
        $basic_settings = $this->basic_settings;
        if ($basic_settings->email_notification) {
            try {

                $mail_data =  [
                    'trx_id'         => $trx_id,
                    'title'          => 'Card Deposit',
                    'request_amount' => get_amount($amount, $charges['card_currency'], $charges['precision_digit']),
                    'payable'        => get_amount($charges['payable'], $charges['from_currency'], $charges['precision_digit']),
                    'charges'        => get_amount($charges['total_charge'], $charges['from_currency'], $charges['precision_digit']),
                    'card_amount'    => get_amount($amount, $charges['card_currency'], $charges['precision_digit']),
                    'card_pan'       => $card->masked_pan ?? '---- ----- ---- ----',
                    'status'         => $card->status ?? '',
                ];
                $user->notify(new CardDepositNotification((object) $mail_data));
            } catch (Exception $e) {
            }
        }
    }

    public function apiErrorHandle($apiErrors)
    {
        $error = ['error' => []];
        if (isset($apiErrors)) {
            if (is_array($apiErrors)) {
                foreach ($apiErrors as $field => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $message) {
                            $error['error'][] = $message;
                        }
                    } else {
                        $error['error'][] = $messages;
                    }
                }
            } else {
                $error['error'][] = $apiErrors;
            }
        }

        $errorMessages = array_map(function ($message) {
            return rtrim($message, '.');
        }, $error['error']);

        $errorString  = implode(', ', $errorMessages);
        $errorString .= '.';

        return Response::error([$errorString ?? __("Something went wrong! Please try again.")]);
    }
}
