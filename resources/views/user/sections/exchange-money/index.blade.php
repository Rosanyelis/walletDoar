
@extends('user.layouts.master')

@push('css')
    
@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Money Exchange")])
@endsection

@section('content')
<div class="row mt-20 mb-20-none">
    <div class="col-xl-7 col-lg-7 mb-20">
        <div class="custom-card exchange-money-card mt-10">
            <div class="card-body">
                <form class="card-form exchange-money-form-doar" action="{{ setRoute('user.exchange.money.submit') }}" method="POST">
                    @csrf
                    <div class="exchange-money-header">
                        <div class="exchange-money-header-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="exchange-money-header-text">
                            <h4 class="exchange-money-header-title">{{ __("Tipo de cambio") }}</h4>
                            <span class="exchange-money-header-rate exchangeRateShow"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 form-group add-money">
                            <label class="exchange-money-label">{{ __("Moneda de origen") }}<span>*</span></label>
                            <div class="exchange-money-amount-wrap">
                                <input type="text" class="form--control exchange-money-input" name="exchange_from_amount" value="{{ old('exchange_from_amount') }}" placeholder="{{ __('Ingrese cantidad...') }}">
                                <div class="currency">
                                    <select class="form--control nice-select exchangeFromCurrency" name="exchange_from_currency">
                                        @foreach ($user_wallets as $item)
                                            <option
                                                value="{{ $item->currency->code }}"
                                                data-id="{{ $item->currency->id }}"
                                                data-rate="{{ $item->currency->rate }}"
                                                data-code="{{ $item->currency->code }}"
                                                data-type="{{ $item->currency->type }}"
                                                data-symbol="{{ $item->currency->symbol }}"
                                                data-balance="{{ $item->balance }}"
                                            >{{ $item->currency->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <p class="d-block mt-2 exchange-money-note fromWalletBalanceShow"></p>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group add-money">
                            <label class="exchange-money-label">{{ __("Moneda de destino") }}<span>*</span></label>
                            <div class="exchange-money-amount-wrap">
                                <input type="text" class="form--control exchange-money-input" name="exchange_to_amount" placeholder="0.00" readonly>
                                <div class="currency">
                                    <select class="form--control nice-select exchangeToCurrency" name="exchange_to_currency">
                                        @foreach ($user_wallets as $item)
                                            <option
                                                value="{{ $item->currency->code }}"
                                                data-id="{{ $item->currency->id }}"
                                                data-rate="{{ $item->currency->rate }}"
                                                data-code="{{ $item->currency->code }}"
                                                data-type="{{ $item->currency->type }}"
                                            >{{ $item->currency->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <div class="note-area exchange-money-note-area">
                                <p class="d-block exchange-money-note limit-show"></p>
                                <p class="d-block exchange-money-note exchange-money-fees-right fees-show"></p>
                            </div>
                        </div>
                    </div>
                    <div class="exchange-money-submit-wrap">
                        <button type="submit" class="exchange-money-btn">{{ __("Cambio de moneda") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-5 col-lg-5 mb-20">
        <div class="custom-card mt-10">
            <div class="dashboard-header-wrapper">
                <h4 class="title add-money-summary-title">{{ __("Resumen") }}</h4>
            </div>
            <div class="card-body add-money-summary-body">
                <div class="preview-list-wrapper add-money-summary-list">
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h6M12 11v6M9 14h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Desde la billetera") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="fromWallet">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("A cambio") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="toExchange exchange-summary-value-red">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 17L3 12l4-5M17 7l4 5-4 5M14 4l-4 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Tipo de cambio") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="rateShow exchange-summary-value-green">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Importe total del cambio") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="requestAmount exchange-summary-value-gold">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Monto convertido") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="receiveAmount exchange-summary-value-green">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Carga total") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="fees exchange-summary-value-gold">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item add-money-summary-item-total">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span class="fw-bold">{{ __("Total a pagar") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="payInTotal exchange-summary-value-gold fw-bold">--</span>
                        </div>
                    </div>
                </div>
                <div class="add-money-summary-footer">
                    <span class="add-money-summary-total payInTotal">--</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="dashboard-list-area mt-20">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __("Money Exchange Log") }}</h4>
        <div class="dashboard-btn-wrapper">
            <div class="dashboard-btn">
                <a href="{{ setRoute('user.transactions.index','money-exchange-log') }}" class="btn--base">{{ __("View More") }}</a>
            </div>
        </div>
    </div>
    @include('user.components.wallets.transation-log', compact('transactions'))
</div>
@endsection

@push('script')
    <script>
        $(document).ready(function(){
            callFunctions() 
            $('.fromWalletBalanceShow').html("{{ __('Saldo disponible') }}: " + $("select[name=exchange_from_currency] :selected").attr("data-symbol") + parseFloat($("select[name=exchange_from_currency] :selected").attr("data-balance")).toFixed(2));
        })
        $('.exchangeFromCurrency').on('change', function(){
            callFunctions()
            $('.fromWalletBalanceShow').html("{{ __('Saldo disponible') }}: " + $("select[name=exchange_from_currency] :selected").attr("data-symbol") + parseFloat($("select[name=exchange_from_currency] :selected").attr("data-balance")).toFixed(2));
        })
        $('.exchangeToCurrency').on('change', function(){
            callFunctions()
        })
        $('input[name=exchange_from_amount]').keyup(function(){ 
            callFunctions()
        })
        function callFunctions() {
            getExchangeRate(); 
            previewDetails();
            getFees();
            getLimit();
        }

        var fixedCharge     = "{{ $charges->fixed_charge ?? 0 }}";
        var percentCharge   = "{{ $charges->percent_charge ?? 0 }}";
        var minLimit        = "{{ $charges->min_limit ?? 0 }}";
        var maxLimit        = "{{ $charges->max_limit ?? 0 }}";
        
        function acceptVar() {
            var exchangeFromAmount = $("input[name=exchange_from_amount]").val();  
            var exchangeFromRate = $("select[name=exchange_from_currency] :selected").attr("data-rate");  
            var exchangeFromCode = $("select[name=exchange_from_currency] :selected").attr("data-code");  
            var exchangeFromType = $("select[name=exchange_from_currency] :selected").attr("data-type"); 

            var exchangeToRate = $("select[name=exchange_to_currency] :selected").attr("data-rate");  
            var exchangeToCode = $("select[name=exchange_to_currency] :selected").attr("data-code");  
            var exchangeToType = $("select[name=exchange_to_currency] :selected").attr("data-type"); 
            if (exchangeFromType == "CRYPTO") {
                var exchangeFromDigit = 8;
            } else {
                var exchangeFromDigit = 2;
            }
            if (exchangeToType == "CRYPTO") {
                var exchangeToDigit = 8;
            } else {
                var exchangeToDigit = 2;
            }

            return {
                exchangeFromAmount: exchangeFromAmount,
                exchangeFromRate: exchangeFromRate,
                exchangeFromCode: exchangeFromCode,
                exchangeFromDigit: exchangeFromDigit,

                exchangeToRate: exchangeToRate,
                exchangeToCode: exchangeToCode,
                exchangeToDigit: exchangeToDigit,
            };
        }
        //calculate exchange rate
        function getExchangeRate(){
            var exchangeRate = parseFloat(acceptVar().exchangeToRate) / parseFloat(acceptVar().exchangeFromRate); 
            $('.exchangeRateShow').html("1 " + acceptVar().exchangeFromCode +" = " + exchangeRate.toFixed(acceptVar().exchangeToDigit) + " " + acceptVar().exchangeToCode);
            var exchangeToConverMmount = acceptVar().exchangeFromAmount*exchangeRate;
            $("input[name=exchange_to_amount]").val(exchangeToConverMmount.toFixed(acceptVar().exchangeToDigit));
        }
        function getLimit(){
            var exchangeFromCode =  acceptVar().exchangeFromCode; 
            var exchangeRate = (1/parseFloat(acceptVar().exchangeToRate)) * parseFloat(acceptVar().exchangeFromRate);
            var min_limit = minLimit;
            var max_limit = maxLimit;
            
            var min_limit_calc = parseFloat(min_limit*exchangeRate);
            var max_limit_clac = parseFloat(max_limit*exchangeRate);
            $('.limit-show').html("{{ __('Límite') }}: " + min_limit_calc.toFixed(2) + " " + exchangeFromCode + " - " + max_limit_clac.toFixed(2) + " " + exchangeFromCode);

        }
        //calculate fees 
        function feesCalculation(){
            var exchangeFromAmount =  acceptVar().exchangeFromAmount;
            var exchangeFromRate =  acceptVar().exchangeFromRate;
            var exchangeFromCode =  acceptVar().exchangeFromCode; 

            var fixedChargeCalculation = parseFloat(exchangeFromRate)*fixedCharge;
            var percentChargeCalculation = parseFloat(percentCharge/100)*parseFloat(exchangeFromAmount*1);
            var totalCharge = fixedChargeCalculation+percentChargeCalculation;

            return {
                fixed_charge: fixedChargeCalculation,
                percent_charge: percentChargeCalculation,
                total_charge: totalCharge,
            };

        }
        function getFees() {
            var exchangeFromCode =  acceptVar().exchangeFromCode;
            var charges = feesCalculation();
            $('.fees-show').html("{{ __('Cargo') }}: " + parseFloat(charges.fixed_charge).toFixed(2) + " " + exchangeFromCode +" + " + parseFloat(percentCharge) + "%" + " = "+ parseFloat(charges.total_charge).toFixed(2) + " " + exchangeFromCode);
        }
        //preview details
        function previewDetails(){
            var exchangeFromAmount =  acceptVar().exchangeFromAmount;
            var exchangeFromRate =  acceptVar().exchangeFromRate;
            var exchangeFromCode =  acceptVar().exchangeFromCode;

            var exchangeToRate =  acceptVar().exchangeToRate;
            var exchangeToCode =  acceptVar().exchangeToCode;
            //exchange rate 
            var exchangeRate = parseFloat(exchangeToRate) / parseFloat(exchangeFromRate);

            $('.fromWallet').html(exchangeFromCode);
            $('.toExchange').html(exchangeToCode);
            $('.rateShow').html("1 " + exchangeFromCode +" = " + exchangeRate.toFixed(acceptVar().exchangeToDigit) + " " + exchangeToCode)
            $('.requestAmount').html(exchangeFromAmount*1 + " " +exchangeFromCode);
            //converted amount
            var convertedAmount = exchangeFromAmount*exchangeRate;
            $('.receiveAmount').html(parseFloat(convertedAmount).toFixed(acceptVar().exchangeToDigit) + " " +exchangeToCode);
            //show total fees
            var charges = feesCalculation();
            $('.fees').html(charges.total_charge.toFixed(2) + " " + exchangeFromCode);
            // Pay In Total
            var pay_in_total = parseFloat(charges.total_charge) + parseFloat(exchangeFromAmount*1);
            $('.payInTotal').text(parseFloat(pay_in_total).toFixed(2) + " " + exchangeFromCode);
        }

    </script>
@endpush