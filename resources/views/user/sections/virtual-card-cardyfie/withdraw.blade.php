@extends('user.layouts.master')

@push('css')
<style>
    .input-group .nice-select .list {
        height: auto !important;
        overflow-y: auto;
    }
</style>
@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __('Withdraw From Card'),
    ])
@endsection

@section('content')
<div class="row mb-20-none">
    <div class="col-xl-7 col-lg-7 mb-20">
        <div class="custom-card">
            <div class="dashboard-header-wrapper">
                <h5 class="title">{{ __('Withdraw Card') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ setRoute('user.cardyfie.virtual.card.withdraw') }}" class="card-form" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 form-group text-center exchange-box">
                            <div class="exchange-area">
                                <code class="d-block text-center"><span>{{ __("Exchange Rate") }}</span> <span class="exchange-rate">--</span></code>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <input type="hidden" name="id" value="{{ $myCard->id }}">
                            <input type="hidden" name="withdraw_amount">
                            <input type="hidden" name="currency">
                            <input type="hidden" name="from_currency">
                            <label>{{ __('Withdraw Amount') }}<span>*</span></label>
                            <div class="input-group max">
                                <input type="text" class="form--control amount number-input" name="amount"
                                    placeholder="{{ __('Enter Amount') }}...">
                                <select class="form--control nice-select" name="card_currency">
                                    @forelse ($card_currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            data-currency-code="{{ $currency->currency_code }}"
                                            data-rate="{{ $currency->rate }}"
                                            data-min-limit="{{ $currency->min_limit }}"
                                            data-max-limit="{{ $currency->max_limit }}"
                                            data-daily-limit="{{ $currency->daily_limit }}"
                                            data-monthly-limit="{{ $currency->monthly_limit }}"
                                            data-universal-package-fee="{{ $currency->fees->cardyfie_universal_card_issues_fee }}"
                                            data-platinum-package-fee="{{ $currency->fees->cardyfie_platinum_card_issues_fee }}"
                                            data-card-withdraw-fixed-fee="{{ $currency->fees->cardyfie_card_withdraw_fixed_fee }}"
                                            data-card-withdraw-percent-fee="{{ $currency->fees->cardyfie_card_withdraw_percent_fee }}"
                                            data-card-maintenance-fixed-fee="{{ $currency->fees->cardyfie_card_maintenance_fixed_fee }}"
                                            data-currency-symbol="{{ $currency->currency_symbol }}">
                                            {{ $currency->currency_code }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <code class="d-block mt-10 trx-limit"></code>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Select Wallet') }}<span>*</span></label>
                            <div class="input-group w-100">
                                <select class="form--control select2-auto-tokenize" name="wallet_currency">
                                    <option value="" disabled>{{ __('Select Currency') }}</option>
                                    @forelse ($user_wallets as $wallet)
                                        <option value="{{ $wallet->id }}"
                                                data-rate="{{ $wallet->currency->rate }}"
                                                data-currency-code="{{ $wallet->currency->code }}"
                                                data-currency-symbol="{{ $wallet->currency->symbol }}">
                                                {{ $wallet->currency->name }} {{ '('. $wallet->balance ." ".$wallet->currency->code.')' }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <code class="d-block mt-10 withdraw-fee"></code>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12">
                        <button type="submit" class="btn--base w-100 fundBtn"><span
                                class="w-100">{{ __('Withdraw') }}</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-5 col-lg-5 mb-20">
        <div class="custom-card mt-10">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ __("Summary") }}</h4>
            </div>
            <div class="card-body">
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-receipt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Entered Amount") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="text--success request-amount">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-money-check-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Conversion Amount") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="text--info conversion-amount">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-battery-half"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Total Fees & Charges") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="text--warning fees">--</span>
                        </div>
                    </div> 
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-money-check-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span class="last">{{ __("Will Get") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="text--info last will-get">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        var WithdrawFeeText            = "{{ __('Withdraw Fee') }}";
        var TotalPayableText          = "{{ __('Will Get') }}";
        var TRXLimitText              = "{{ __('Limit') }}";
        var DailyLimitText            = "{{ __('Daily Limit') }}";
        var MonthlyLimitText          = "{{ __('Monthly Limit') }}";
        var RemainingDailyLimitText   = "{{ __('Remaining Daily Limit') }}";
        var RemainingMonthlyLimitText = "{{ __('Remaining Monthly Limit') }}";

        $(document).ready(function() {
            acceptVar();
            getExchangeRate();
            getFees();
            getLimits();
        });

        $("input[name=amount]").keyup(function() {
            acceptVar();
            getFees();
            getExchangeRate();
            getLimits();
            preview();
        });

        $("select[name=card_currency]").change(function() {
            acceptVar();
            getExchangeRate();
            getFees();
            getLimits();
            preview();
        });
        $("select[name=wallet_currency]").change(function() {
            acceptVar();
            getExchangeRate();
            getFees();
            getLimits();
            preview();
        });

        // $(document).on('click', '.btn-issue', function() {
        //     submit()
        // });

        function acceptVar() {
            var amount                          = $("input[name=amount]").val();
            var cardCurrency                    = $("select[name=card_currency] :selected");
            var cardCurrencyId                  = cardCurrency.val();
            var cardCurrencyCode                = cardCurrency.data('currency-code');
            var cardCurrencyRate                = cardCurrency.data('rate');
            var cardCurrencyMinLimit            = cardCurrency.data('min-limit');
            var cardCurrencyMaxLimit            = cardCurrency.data('max-limit');
            var cardCurrencyDailyLimit          = cardCurrency.data('daily-limit');
            var cardCurrencyMonthlyLimit        = cardCurrency.data('monthly-limit');
            var cardCurrencyUniversalPackageFee = cardCurrency.data('universal-package-fee');
            var cardCurrencyPlatinumPackageFee  = cardCurrency.data('platinum-package-fee');
            var cardCurrencyDepositFixedFee     = cardCurrency.data('card-deposit-fixed-fee');
            var cardCurrencyWithdrawFixedFee    = cardCurrency.data('card-withdraw-fixed-fee');
            var cardCurrencyWithdrawPercentFee    = cardCurrency.data('card-withdraw-percent-fee');
            var cardCurrencyMaintenanceFixedFee = cardCurrency.data('card-maintenance-fixed-fee');
            var cardCurrencySymbol              = cardCurrency.data('currency-symbol');

            var wallet               = $("select[name=wallet_currency] :selected");
            var walletId             = wallet.val();
            var walletRate           = wallet.data('rate');
            var walletCurrencyCode   = wallet.data('currency-code');
            var walletCurrencySymbol = wallet.data('currency-symbol');

            return {
                amount                         : amount,
                cardCurrency                   : cardCurrency,
                cardCurrencyId                 : cardCurrencyId,
                cardCurrencyCode               : cardCurrencyCode,
                cardCurrencyRate               : cardCurrencyRate,
                cardCurrencyMinLimit           : cardCurrencyMinLimit,
                cardCurrencyMaxLimit           : cardCurrencyMaxLimit,
                cardCurrencyDailyLimit         : cardCurrencyDailyLimit,
                cardCurrencyMonthlyLimit       : cardCurrencyMonthlyLimit,
                cardCurrencyUniversalPackageFee: cardCurrencyUniversalPackageFee,
                cardCurrencyPlatinumPackageFee : cardCurrencyPlatinumPackageFee,
                cardCurrencyDepositFixedFee    : cardCurrencyDepositFixedFee,
                cardCurrencyWithdrawFixedFee   : cardCurrencyWithdrawFixedFee,
                cardCurrencyWithdrawPercentFee   : cardCurrencyWithdrawPercentFee,
                cardCurrencyMaintenanceFixedFee: cardCurrencyMaintenanceFixedFee,
                cardCurrencySymbol             : cardCurrencySymbol,

                wallet              : wallet,
                walletId            : walletId,
                walletRate          : walletRate,
                walletCurrencyCode  : walletCurrencyCode,
                walletCurrencySymbol: walletCurrencySymbol,

            };
        }

        function getExchangeRate() {
            var card_currency      = acceptVar().cardCurrencyCode;
            var card_currency_rate = acceptVar().cardCurrencyRate;

            var wallet_currency      = acceptVar().walletCurrencyCode;
            var wallet_currency_rate = acceptVar().walletRate;
            var rate                 = parseFloat(wallet_currency_rate) / parseFloat(card_currency_rate);


            if (wallet_currency == null || wallet_currency == "" || card_currency == null || card_currency == "") {
                return false;
            }

            $('.exchange-rate').html("1 " + card_currency + " = " + parseFloat(rate).toFixed(3) + " " + wallet_currency);

            return rate;
        }


        function feesCalculation() {
            var exchange_rate = getExchangeRate();
            var amount        = $("input[name=amount]").val();
            amount == "" ? amount = 0 : amount = amount;

            card_withdraw_fee = acceptVar().cardCurrencyWithdrawFixedFee;
            card_withdraw_percent_fee = acceptVar().cardCurrencyWithdrawPercentFee;

            if ($.isNumeric(card_withdraw_fee)) {
                // Process Calculation
                var exchanged_amount   = parseFloat(amount) * parseFloat(exchange_rate);
                var card_withdraw_calc = parseFloat(card_withdraw_fee) * parseFloat(exchange_rate);
                var card_withdraw_percent_calc = (card_withdraw_percent_fee / 100) * exchanged_amount
                var total_charge       = parseFloat(card_withdraw_calc) + parseFloat(card_withdraw_percent_calc);
                    total_charge       = parseFloat(total_charge).toFixed(8);
                var will_get_amount    = parseFloat(exchanged_amount) - parseFloat(total_charge);
                // return total_charge;
                return {
                    card_withdraw_calc: parseFloat(card_withdraw_calc).toFixed(8),
                    card_withdraw_percent_calc: parseFloat(card_withdraw_percent_fee).toFixed(8),
                    total             : parseFloat(total_charge).toFixed(8),
                    exchanged         : parseFloat(exchanged_amount).toFixed(8),
                    will_get          : parseFloat(will_get_amount).toFixed(8),
                };
            } else {
                // return "--";
                return false;
            }
        }

        function getFees() {
            var wallet_currency = acceptVar().walletCurrencyCode;
            var card_currency   = acceptVar().cardCurrencyCode;
            var charges         = feesCalculation();
            var amount          = acceptVar().amount;


            if (charges == false || wallet_currency == null || wallet_currency == "" || card_currency == null || card_currency == "") {
                return false;
            }

            $(".withdraw-fee").html(WithdrawFeeText+" :"+parseFloat(charges.card_withdraw_calc).toFixed(3) + " " +wallet_currency+" + "+parseFloat(charges.card_withdraw_percent_calc).toFixed(2)+"%");
        }

        function stepOneValidation(){
            let cardType = acceptVar().cardType;
            if(cardType){
                $('.step-two').removeClass('disabled');
            }
        }

        function stepTwoValidation(){
            let isValid = true;
            let cardAmount     = acceptVar().amount;
            let wallet         = acceptVar().walletId;
            let cardCurrency   = acceptVar().cardCurrencyId;

            if (!cardAmount || cardAmount <= 0) {
                isValid = false;
            }

            if (!wallet) {
                isValid = false;
            }

            if (!cardCurrency) {
                isValid = false;
            }

        }

        function getLimits(){
            var exchange_rate = getExchangeRate();
            var min_limit     = acceptVar().cardCurrencyMinLimit;
            var max_limit     = acceptVar().cardCurrencyMaxLimit;
            var daily_limit   = acceptVar().cardCurrencyDailyLimit;
            var monthly_limit = acceptVar().cardCurrencyMonthlyLimit;
            var wallet_currency = acceptVar().walletCurrencySymbol;
            var card_currency   = acceptVar().cardCurrencyCode;

            min_limit     = min_limit;
            max_limit     = max_limit;
            daily_limit   = daily_limit;
            monthly_limit = monthly_limit;

            if (min_limit == null || min_limit == "" || wallet_currency == null || wallet_currency == "" || card_currency == null || card_currency == "") {
                return false;
            }

            $(".trx-limit").html(TRXLimitText+" :"+parseFloat(min_limit).toFixed(3) + " " +
                card_currency+" - "+parseFloat(max_limit).toFixed(3) + " " +
                card_currency);
            $(".daily-limit").html(DailyLimitText+" :"+parseFloat(daily_limit).toFixed(3) + " " +
                card_currency);
            $(".monthly-limit").html(MonthlyLimitText+" :"+parseFloat(monthly_limit).toFixed(3) + " " +
                card_currency);

        }

        function preview(){
            var wallet_currency = acceptVar().walletCurrencyCode;
            var card_currency   = acceptVar().cardCurrencyCode;
            var charges         = feesCalculation();
            var amount          = acceptVar().amount;
            amount == "" ? amount = 0 : amount = amount;

            if (wallet_currency == null || wallet_currency == "" || card_currency == null || card_currency == "") {
                return false;
            }

            $(".request-amount").html(amount+" "+card_currency);
            $(".conversion-amount").html(parseFloat(charges.exchanged).toFixed(3) + " " +
                wallet_currency);
            $('.fees').text(parseFloat(charges.total).toFixed(3) + " " + wallet_currency);
            $('.will-get').text(parseFloat(charges.will_get).toFixed(3) + " " + wallet_currency);

            $("input[name=withdraw_amount]").val(amount);
            $("input[name=currency]").val(card_currency);
            $("input[name=from_currency]").val(wallet_currency);

        }

    </script>
@endpush
