@extends('user.layouts.master') 
@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection 
@section('content')
<div class="row mb-20-none">
    <div class="col-xl-7 col-lg-7 mb-20">
        <div class="custom-card mt-10">
            <div class="card-body p-3">
                <form action="{{ setRoute('user.request.money.submit') }}" method="POST" class="card-form add-money-form-doar request-money-form-doar">
                    @csrf
                    <div class="request-money-header">
                        <div class="request-money-header-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="request-money-header-text">
                            <h4 class="request-money-header-title">{{ __("Solicitud de dinero") }}</h4>
                            <p class="request-money-header-subtitle">{{ __("Solicita dinero a un familiar, cliente o amigo") }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 form-group add-money">
                            <label class="add-money-label">{{ __("Cantidad de solicitud") }}<span>*</span></label>
                            <div class="add-money-amount-wrap request-money-amount-wrap">
                                <span class="request-money-amount-prefix" aria-hidden="true">$</span>
                                <input type="text" name="amount" class="form--control add-money-input" value="{{ old('amount') }}" id="amountInput" placeholder="{{ __('Importe del remitente') }}">
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
                            <p class="d-block mt-2 add-money-note text-end walletBalanceShow"></p>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label class="add-money-label">{{ __("Observaciones") }} <span class="request-money-optional">({{ __("Opcional") }})</span></label>
                            <textarea class="form--control" name="remark" rows="3" placeholder="{{ __("Explique los propósitos de la solicitud aquí...") }}"></textarea>
                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <div class="note-area add-money-note-area">
                                <p class="d-block add-money-note limit-show">--</p>
                                <p class="d-block add-money-note add-money-fees-right fees-show">--</p>
                            </div>
                        </div>
                    </div>
                    <div class="add-money-submit-wrap">
                        <button type="submit" class="add-money-btn">{{ __("Pedir dinero") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-5 col-lg-5 mb-20">
        <div class="custom-card mt-10 request-money-summary-card">
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
                                    <span>{{ __("Monto solicitado") }}</span>
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
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Tarifas y cargos totales") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="fees request-money-summary-fees">--</span>
                        </div>
                    </div>
                    <div class="preview-list-item add-money-summary-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon add-money-summary-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Obtendrá") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="will-get request-money-summary-willget">--</span>
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
                    <span class="add-money-summary-total pay-in-total">--</span>
                </div>
            </div>
        </div>
    </div>
</div>
  <div class="dashboard-list-area mt-60">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __("Latest Transactions") }}</h4>
        <div class="dashboard-btn-wrapper">
            <div class="dashboard-btn">
                <a href="{{ setRoute('user.transactions.request.money') }}" class="btn--base">{{ __("View More") }}</a>
            </div>
        </div>
    </div>
    @include('user.components.wallets.request-money-transation-log', compact('transactions'))
  </div>
</div>
@endsection
@push('script')
    <script>
        var fixedCharge     = "{{ $charges->fixed_charge ?? 0 }}";
        var percentCharge   = "{{ $charges->percent_charge ?? 0 }}";
        var minLimit        = "{{ $charges->min_limit ?? 0 }}";
        var maxLimit        = "{{ $charges->max_limit ?? 0 }}";
        $(document).ready(function(){ 
            walletBalance();
            getLimit();
            getFees();
            getPreview();
        }); 
        $('select[name=request_currency]').on('change',function(){    
            walletBalance();
            getLimit();
            getFees();
            getPreview();
        });
        
        $("input[name=amount]").keyup(function(){
                getFees();
                getPreview();
        }); 
        function walletBalance(){
            $('.walletBalanceShow').html("Saldo disponible: " + $("select[name=request_currency] :selected").attr("data-symbol") + parseFloat($("select[name=request_currency] :selected").attr("data-balance")).toFixed(2));
        }
         //minimum and maxmimum money limite
         function getLimit(){
            var senderCurrencyCode =  acceptVar().senderCurrencyCode; 
            var exchangeRate = parseFloat(acceptVar().senderRate);
            var min_limit = minLimit;
            var max_limit = maxLimit;
            
            var min_limit_calc = parseFloat(min_limit*exchangeRate);
            var max_limit_clac = parseFloat(max_limit*exchangeRate);
            $('.limit-show').html("Límite: " + min_limit_calc.toFixed(2) + " " + senderCurrencyCode + " - " + max_limit_clac.toFixed(2) + " " + senderCurrencyCode);

        }

        // get variables 
        function acceptVar() {
            var senderCurrencyCode = $("select[name=request_currency] :selected").val();
            var senderRate = $("select[name=request_currency] :selected").attr("data-rate"); 

            return {
                    senderCurrencyCode:senderCurrencyCode,
                    senderRate:senderRate,
            };
        }

         //calculate fees 
         function feesCalculation(){
            var senderAmount = $("input[name=amount]").val();
            var senderCurrencyCode =  acceptVar().senderCurrencyCode;
            var senderRate =  acceptVar().senderRate;

            var fixedChargeCalculation = parseFloat(senderRate)*fixedCharge;
            var percentChargeCalculation = parseFloat(percentCharge/100)*parseFloat(senderAmount*1);
            var totalCharge = fixedChargeCalculation+percentChargeCalculation;

            return {
                fixed_charge: fixedChargeCalculation,
                percent_charge: percentChargeCalculation,
                total_charge: totalCharge,
            };

        }
        function getFees() {
            var senderCurrencyCode =  acceptVar().senderCurrencyCode;
            var charges = feesCalculation();
            $('.fees-show').html("Cargo: " + parseFloat(charges.fixed_charge).toFixed(2) + " " + senderCurrencyCode + " + " + parseFloat(percentCharge) + "% = " + parseFloat(charges.total_charge).toFixed(2) + " " + senderCurrencyCode);
        }

        function getPreview() {
                var senderAmount = $("input[name=amount]").val();
                
                var charges = feesCalculation();
                
                var senderRate = acceptVar().senderRate; 
                // var receiver_currency = acceptVar().rCurrency;
                senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

                // Sending Amount
                $('.request-amount').text(parseFloat(senderAmount).toFixed(2) + " " + acceptVar().senderCurrencyCode);

                // Fees
                $('.fees').text(parseFloat(charges.total_charge).toFixed(2) + " " + acceptVar().senderCurrencyCode);

                // will get amount
                $('.will-get').text(parseFloat(senderAmount).toFixed(2) + " " + acceptVar().senderCurrencyCode);

                // Pay In Total 
                var receiverAmount = parseFloat(senderAmount)+parseFloat(charges.total_charge);
                $('.pay-in-total').text(parseFloat(receiverAmount).toFixed(2) + " " + acceptVar().senderCurrencyCode);

            }
    </script>
    <script>
        var amountInput = document.getElementById('amountInput');
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                var inputValue = this.value;
                var numericValue = inputValue.replace(/[^0-9.]/g, '');
                this.value = numericValue;
            });
        }
    </script>
@endpush