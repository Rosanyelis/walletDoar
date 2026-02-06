@extends('user.layouts.master') 
@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("Add Money")])
@endsection 
@section('content') 
<div class="row mb-20-none">
    <div class="col-xl-7 col-lg-7 mb-20">
        <div class="custom-card mt-10">
           <div class="dashboard-header-wrapper">
               <h4 class="title">{{ $page_title }}</h4>
           </div>
           <div class="card-body p-3">
                <form class="card-form add-money-form-doar" action="{{ setRoute("user.add.money.submit") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 form-group">
                            <div class="exchange-area exchange-area-card justify-content-between">
                                <div class="exchange-area-text">
                                    <span class="exchange-area-label">{{ __("Tipo de cambio") }}</span>
                                    <span class="exchange-area-rate rate-show">--</span>
                                </div>
                                <div class="exchange-area-logo">
                                    @include('user.sections.add-money.partials.exchange-banner-logo')
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group add-money">
                            <label class="add-money-label">{{ __("Cantidad") }}<span>*</span></label>
                            <div class="add-money-amount-wrap">
                                <input type="text" class="form--control add-money-input" name="amount" value="{{ old('amount') }}" id="amountInput" placeholder="{{ __('Ingrese el monto') }}">
                                <div class="currency">
                                    <select class="nice-select add-money-currency-select" name="request_currency">
                                        @foreach ($user_wallets ?? [] as $item)
                                            <option
                                                value="{{ $item->currency->code }}"
                                                data-id="{{ $item->currency->id }}"
                                                data-rate="{{ $item->currency->rate }}"
                                                data-symbol="{{ $item->currency->symbol }}"
                                                data-type="{{ $item->currency->type }}"
                                                data-balance="{{ $item->balance }}"
                                                {{ get_default_currency_code() == $item->currency->code ? 'selected' : '' }}
                                            >{{ $item->currency->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <p class="d-block mt-1 add-money-note walletBalanceShow"></p>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group payment-gateway-select">
                            <label class="add-money-label">{{ __("Pasarela de pago") }}<span>*</span></label>
                            <select class="form--control add-money-select select2-auto-tokenize" name="gateway_currency">
                                @foreach ($payment_gateways ?? [] as $gateway)
                                    @foreach ($gateway->currencies ?? [] as $item)
                                        <option
                                            value="{{ $item->alias }}"
                                            data-currency="{{ $item->currency_code }}"
                                            data-min_amount="{{ $item->min_limit }}"
                                            data-max_amount="{{ $item->max_limit }}"
                                            data-percent_charge="{{ $item->percent_charge }}"
                                            data-fixed_charge="{{ $item->fixed_charge }}"
                                            data-rate="{{ $item->rate }}"
                                        >{{ $item->name }}{{ $item->gateway->isManual() ? ' (' . __('Manual') . ')' : '' }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <div class="note-area add-money-note-area">
                                <p class="d-block add-money-note limit-show">--</p>
                                <p class="d-block add-money-note add-money-fees-right fees-show">--</p>
                            </div>
                        </div>
                    </div>
                    <div class="add-money-submit-wrap">
                        <button type="submit" class="add-money-btn">{{ __("Confirmar") }}</button>
                        <p class="d-block add-money-cost-below fees-show">--</p>
                    </div>
                </form>
           </div>
        </div>
    </div>
    <div class="col-xl-5 col-lg-5 mb-20">
        <div class="custom-card  mt-10">
            <div class="dashboard-header-wrapper">
                <h4 class="title add-money-summary-title">{{ __("Summary") }}</h4>
            </div>
            <!-- <div class="card-body p-3"> -->
                
            <div class="card-body add-money-summary-body">
                <div class="preview-list-wrapper add-money-summary-list">
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Monto ingresado") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="request-amount">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 17L3 12l4-5M17 7l4 5-4 5M14 4l-4 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Monto de conversión") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="conversion-amount">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Tarifas y cargos totales") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="fees">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item add-money-summary-item-total">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h6M12 11v6M9 14h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span class="fw-bold">{{ __("Monto total a pagar") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="pay-in-total fw-bold">--</span>
                        </div>
                    </div>
                </div>
                <div class="add-money-summary-footer">
                    <span class="add-money-summary-cost fees-show">--</span>
                    <span class="add-money-summary-total pay-in-total">--</span>
                </div>
            </div>
                
            <!-- </div> -->
        </div>
    </div>
</div>
<div class="dashboard-list-area mt-60 mb-30">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __("Latest Transactions") }}</h4>
        <div class="dashboard-btn-wrapper">
            <div class="dashboard-btn">
                <a href="{{ setRoute('user.transactions.index','add-money-log') }}" class="btn--base">{{ __("View More") }}</a>
            </div>
        </div>
    </div>
</div> 
@include('user.components.wallets.transation-log', compact('transactions')) 
@endsection
@push('script')
<script> 

   $('select[name=gateway_currency]').on('change',function(){
       getExchangeRate($(this));
       getLimit();
       getFees();
       getPreview();
   });
   $(document).ready(function(){
       getExchangeRate();
       getLimit();
       getFees();   
       walletBalance();
   });
   $('select[name=request_currency]').on('change',function(){   
       getExchangeRate();
       getLimit();
       getFees();
       getPreview(); 
       walletBalance();
   });
   $("input[name=amount]").keyup(function(){
        getFees();
        getPreview();
   }); 
   function walletBalance(){ 
    $('.walletBalanceShow').html("Available balance: " + $("select[name=request_currency] :selected").attr("data-symbol") + parseFloat($("select[name=request_currency] :selected").attr("data-balance")).toFixed(2));
   }
   // calculate exchange rate 
   function getExchangeRate(event) {
       var element = event;
       var gatewayCode = acceptVar().gatewayCode;
       var gatewayRate = acceptVar().gatewayRate;
       var gatewayMinAmount = acceptVar().gatewayMinAmount;
       var gatewayMaxAmount = acceptVar().gatewayMaxAmount; 
       var senderRate = acceptVar().senderRate; 

       var exchangeRate = (1/senderRate)*gatewayRate; 

       $('.rate-show').html("1 " + acceptVar().senderCurrencyCode + " = " + parseFloat(exchangeRate).toFixed(3) + " " + gatewayCode);
   }
   //minimum and maxmimum money limite
   function getLimit() {
       var gateway_currency_code = acceptVar().gatewayCode;
       var gateway_currency_rate = acceptVar().gatewayRate;
       var senderRate = acceptVar().senderRate; 
       var min_limit = acceptVar().gatewayMinAmount;
       var max_limit =acceptVar().gatewayMaxAmount;
       
       var exchangeRate = (1/senderRate)*gateway_currency_rate;

       if($.isNumeric(min_limit) || $.isNumeric(max_limit)) {
           var min_limit_calc = parseFloat(min_limit/exchangeRate).toFixed(2); 
           var max_limit_clac = parseFloat(max_limit/exchangeRate).toFixed(2);
           $('.limit-show').html("Limit: " + min_limit_calc + " " + acceptVar().senderCurrencyCode + " - " + max_limit_clac + " " + acceptVar().senderCurrencyCode);
           return {
               minLimit:min_limit_calc,
               maxLimit:max_limit_clac,
           };
       }else {
           $('.limit-show').html("--");
           return {
               minLimit:0,
               maxLimit:0,
           };
       }
   }

   // get variables 
   function acceptVar() {
       var senderCurrencyCode = $("select[name=request_currency] :selected").val();
       var senderRate = $("select[name=request_currency] :selected").attr("data-rate"); 
       var senderCurrencyType = $("select[name=request_currency] :selected").attr("data-type"); 

       var selectedVal = $("select[name=gateway_currency] :selected");
       var gatewayCode = $("select[name=gateway_currency] :selected").attr("data-currency");
       var gatewayRate = $("select[name=gateway_currency] :selected").attr("data-rate");
       var gatewayMinAmount = $("select[name=gateway_currency] :selected").attr("data-min_amount");
       var gatewayMaxAmount = $("select[name=gateway_currency] :selected").attr("data-max_amount");
       var gatewayFixedCharge = $("select[name=gateway_currency] :selected").attr("data-fixed_charge");
       var gatewayPercentCharge = $("select[name=gateway_currency] :selected").attr("data-percent_charge");

       if (senderCurrencyType == "CRYPTO") {
        var senderCurrencyDigit = 8;
       } else {
        var senderCurrencyDigit = 2;
       }

       return {
            gatewayCode:gatewayCode,
            gatewayRate:gatewayRate,
            gatewayMinAmount:gatewayMinAmount,
            gatewayMaxAmount:gatewayMaxAmount,
            gatewayFixedCharge:gatewayFixedCharge,
            gatewayPercentCharge:gatewayPercentCharge,
            selectedVal:selectedVal,

            senderCurrencyCode:senderCurrencyCode,
            senderRate:senderRate,
            senderCurrencyDigit:senderCurrencyDigit,

       };
   }

   function feesCalculation() {
       var gateway_currency_code = acceptVar().gatewayCode;
       var gateway_currency_rate = acceptVar().gatewayRate;
       var sender_amount = $("input[name=amount]").val();
       var senderRate = acceptVar().senderRate; 

       sender_amount == "" ? (sender_amount = 0) : (sender_amount = sender_amount);

       var exchangeRate = ( 1 / senderRate) * gateway_currency_rate; 

       var fixed_charge = acceptVar().gatewayFixedCharge;
       var percent_charge = acceptVar().gatewayPercentCharge;
       if ($.isNumeric(percent_charge) && $.isNumeric(fixed_charge) && $.isNumeric(sender_amount)) {
           // Process Calculation
           var fixed_charge_calc = parseFloat(fixed_charge);
           var percent_charge_calc = ((parseFloat(sender_amount) / 100) * parseFloat(percent_charge)) * parseFloat(exchangeRate);
           var total_charge = parseFloat(fixed_charge_calc) + parseFloat(percent_charge_calc);
           total_charge = parseFloat(total_charge).toFixed(2);

           return {
               total: total_charge,
               fixed: fixed_charge_calc,
               percent: percent_charge,
           };
       } else {
           // return "--";
           return false;
       }
   }

   function getFees() {
       var gateway_currency_code = acceptVar().gatewayCode;
       var percent = acceptVar().gatewayPercentCharge;
       var charges = feesCalculation();
       if (charges == false) {
           return false;
       }
       $(".fees-show").html("Charge: " + parseFloat(charges.fixed).toFixed(2) + " " + gateway_currency_code + " + " + parseFloat(charges.percent).toFixed(2) + "%");
   }
   function getPreview() {
           var senderAmount = $("input[name=amount]").val();
           var gateway_currency_code = acceptVar().gatewayCode;
           var gateway_currency_rate = acceptVar().gatewayRate;
           
            var gatewayRate = acceptVar().gatewayRate;
           var senderRate = acceptVar().senderRate; 
           var exchangeRate = (1/senderRate)*gatewayRate; 
           // var receiver_currency = acceptVar().rCurrency;
           senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

           // Sending Amount
           $('.request-amount').text(parseFloat(senderAmount).toFixed(acceptVar().senderCurrencyDigit) + " " + acceptVar().senderCurrencyCode);
            // conversion-amount
             var totalPay = parseFloat(senderAmount) * parseFloat(exchangeRate)
            $('.conversion-amount').text(parseFloat(totalPay).toFixed(2) + " " + gateway_currency_code);
           // Fees
           var charges = feesCalculation();

           $('.fees').text(charges.total + " " + gateway_currency_code);

           // Pay In Total
           var totalPay = parseFloat(senderAmount) * parseFloat(exchangeRate);
            var pay_in_total = parseFloat(charges.total) + parseFloat(totalPay);
           $('.pay-in-total').text(parseFloat(pay_in_total).toFixed(2) + " " + gateway_currency_code);

       }


</script>
<script>
    // Get the input element
    var amountInput = document.getElementById('amountInput');

    // Add event listener for input event
    amountInput.addEventListener('input', function() {
        // Get the input value
        var inputValue = amountInput.value;

        // Remove any non-numeric characters except decimal point from the input value
        var numericValue = inputValue.replace(/[^0-9.]/g, '');

        // Update the input field value with the cleaned numeric value
        amountInput.value = numericValue;
    });
</script>
@endpush