<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\VirtualCardApi;
use Illuminate\Support\Facades\Validator;
use App\Models\VirtualCardProviderCurrency;

class VirtualCardController extends Controller
{
    public function index()
    {
        $page_title = __("Setup Virtual Card Api");
        $apis       = VirtualCardApi::get();
        return view('admin.sections.virtual-card.index', compact(
            'page_title',
            'apis',
        ));
    }

    public function edit($slug)
    {
        $page_title = __("Edit Virtual Card Api");
        $provider   = VirtualCardApi::where('provider_slug', $slug)->first();

        return view('admin.sections.virtual-card.edit', compact(
            'page_title',
            'provider',
        ));
    }

    public function updateProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_method'      => 'required|in:cardyfie',
            'provider_image'  => "nullable|mimes:png,jpg,jpeg,webp,svg",
            'public_key'      => 'required_if:api_method,cardyfie',
            'secret_key'      => 'required_if:api_method,cardyfie',
            'sandbox_url'     => 'required_if:api_method,cardyfie',
            'production_url'  => 'required_if:api_method,cardyfie',
            'webhook_secret'  => 'required_if:api_method,cardyfie',
            'mode'            => 'required_if:api_method,cardyfie',
            'card_details'    => 'required|string',
            'universal_image' => "nullable|mimes:png,jpg,jpeg,webp,svg",
            'platinum_image'  => "nullable|mimes:png,jpg,jpeg,webp,svg"
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $request->merge(['name' => $request->api_method]);
        $data              = array_filter($request->except('_token', 'api_method', '_method', 'card_details', 'universal_image', 'platinum_image', 'provider_image', 'currency'));
        $api               = VirtualCardApi::first();
        $api->card_details = $request->card_details;
        // $api->card_limit   = $request->card_limit;
        $api->config       = $data;

        if ($api->supported_currencies && isset($request->currency)) {
            foreach ($request->currency as $currency) {
                $provider_currency = VirtualCardProviderCurrency::where('currency_code', $currency)->first();
                if (!$provider_currency) {
                    $fees = [
                        'cardyfie_universal_card_issues_fee'  => 0.00000000,
                        'cardyfie_platinum_card_issues_fee'   => 0.00000000,
                        'cardyfie_card_deposit_fixed_fee'     => 0.00000000,
                        'cardyfie_card_deposit_percent_fee'     => 0.00000000,
                        'cardyfie_card_withdraw_fixed_fee'    => 0.00000000,
                        'cardyfie_card_withdraw_percent_fee'    => 0.00000000,
                        'cardyfie_card_maintenance_fixed_fee' => 0.00000000,
                    ];
                    try {
                        VirtualCardProviderCurrency::create([
                            'provider_id'     => $api->id,
                            'currency_code'   => $currency,
                            'currency_symbol' => null,
                            'image'           => null,
                            'fees'            => $fees,
                            'status'          => true,
                        ]);
                    } catch (Exception $e) {
                        return back()->with(['error' => [__('Ops! Failed To update provider')]]);
                    }
                } else {

                    try {
                        $provider_currency->update([
                            'status' => true,
                        ]);
                    } catch (Exception $e) {
                        return back()->with(['error' => [__('Ops! Failed To update provider')]]);
                    }
                }
            }
        }

        if ($request->hasFile("universal_image")) {
            try {
                $universal_image       = get_files_from_fileholder($request, "universal_image");
                $upload_file = upload_files_from_path_dynamic($universal_image, "card-api");
                $api->universal_image  = $upload_file;
            } catch (Exception $e) {
                return back()->with(['error' => [__('Ops! Failed To Upload universal image.')]]);
            }
        }

        if ($request->hasFile("platinum_image")) {
            try {
                $platinum_image       = get_files_from_fileholder($request, "platinum_image");
                $upload_file = upload_files_from_path_dynamic($platinum_image, "card-api");
                $api->platinum_image  = $upload_file;
            } catch (Exception $e) {
                return back()->with(['error' => [__('Ops! Failed To Upload platinum image.')]]);
            }
        }

        if ($request->hasFile("provider_image")) {
            try {
                $image               = get_files_from_fileholder($request, "provider_image");
                $upload_file         = upload_files_from_path_dynamic($image, "card-providers-images");
                $api->provider_image = $upload_file;
            } catch (Exception $e) {
                return back()->with(['error' => [__('Ops! Failed To Upload Image.')]]);
            }
        }

        $api->save();

        return back()->with(['success' => [__('Card API Has Been Updated.')]]);
    }

    public function updateProviderCurrency(Request $request)
    {
        $validated = $request->validate([
            'id'        => 'required',
            'min_limit' => 'required',
            'max_limit' => 'required',
              // 'daily_limit'                         => 'required',
              // 'monthly_limit'                       => 'required',
            'cardyfie_universal_card_issues_fee'  => 'required',
            'cardyfie_platinum_card_issues_fee'   => 'required',
            'cardyfie_card_deposit_fixed_fee'     => 'required',
            'cardyfie_card_deposit_percent_fee'     => 'required',
            'cardyfie_card_withdraw_fixed_fee'    => 'required',
            'cardyfie_card_withdraw_percent_fee'    => 'required',
            'cardyfie_card_maintenance_fixed_fee' => 'required',
            'exchange_rate'                       => 'required',
            'symbol'                              => 'required',
        ]);

        $provider_currency = VirtualCardProviderCurrency::where('id', $validated['id'])->first();

        $fees = [
            'cardyfie_universal_card_issues_fee'  => $validated['cardyfie_universal_card_issues_fee'],
            'cardyfie_platinum_card_issues_fee'   => $validated['cardyfie_platinum_card_issues_fee'],
            'cardyfie_card_deposit_fixed_fee'     => $validated['cardyfie_card_deposit_fixed_fee'],
            'cardyfie_card_deposit_percent_fee'     => $validated['cardyfie_card_deposit_percent_fee'],
            'cardyfie_card_withdraw_fixed_fee'    => $validated['cardyfie_card_withdraw_fixed_fee'],
            'cardyfie_card_withdraw_percent_fee'    => $validated['cardyfie_card_withdraw_percent_fee'],
            'cardyfie_card_maintenance_fixed_fee' => $validated['cardyfie_card_maintenance_fixed_fee'],
        ];


        try {
            $provider_currency->update([
                'min_limit' => $validated['min_limit'],
                'max_limit' => $validated['max_limit'],
                  // 'daily_limit'     => $validated['daily_limit'],
                  // 'monthly_limit'   => $validated['monthly_limit'],
                'rate'            => $validated['exchange_rate'],
                'currency_symbol' => $validated['symbol'],
                'fees'            => $fees,
                'status'          => true,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__("Something went wrong! Please try again.")]]);
        }

        return back()->with(['success' => [__('Card API Has Been Updated.')]]);
    }

    public function currencyUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id'   => 'required',
            'currency_code' => 'required',
            'status'        => 'required',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return Response::error($validator->errors());
        }

        $validated = $validator->validate();

        $provider_currency = VirtualCardProviderCurrency::where('currency_code', $validated['currency_code'])
            ->where('provider_id', $validated['provider_id'])
            ->first();

        try {
            $provider_currency->update([
                'status' => $validated['status']
            ]);
        } catch (Exception $e) {
            $error = ['error' => [__('Something went wrong! Please try again.')]];
            return Response::error($error, null, 500);
        }

        $success = ['success' => [__('Currency removed successfully')]];
        return Response::success($success);
    }
}
