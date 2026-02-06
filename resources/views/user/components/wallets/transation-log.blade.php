<div class="dashboard-list-wrapper">
   @forelse ($transactions as $item)
   <div class="dashboard-list-item-wrapper">
    <div class="dashboard-list-item {{ $item->status == 1 ? "receive": "sent" }} ">
        <div class="dashboard-list-left">
            <div class="dashboard-list-user-wrapper">
                <div class="dashboard-list-user-icon">
                    <i class="">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.34615 2.46795C1.34615 1.84801 1.84801 1.34615 2.46795 1.34615H8.75C9.36995 1.34615 9.87179 1.84801 9.87179 2.46795V3.36538C9.87179 3.73711 10.1732 4.03846 10.5449 4.03846C10.9166 4.03846 11.2179 3.73711 11.2179 3.36538V2.46795C11.2179 1.10456 10.1134 0 8.75 0H2.46795C1.10456 0 0 1.10456 0 2.46795V15.0321C0 16.3954 1.10456 17.5 2.46795 17.5H8.75C10.1134 17.5 11.2179 16.3954 11.2179 15.0321V14.1346C11.2179 13.7629 10.9166 13.4615 10.5449 13.4615C10.1732 13.4615 9.87179 13.7629 9.87179 14.1346V15.0321C9.87179 15.652 9.36995 16.1538 8.75 16.1538H2.46795C1.84801 16.1538 1.34615 15.652 1.34615 15.0321V2.46795ZM10.5449 6.73077C10.4212 6.73077 10.3205 6.83147 10.3205 6.95513V10.5449C10.3205 10.669 10.4208 10.7692 10.5449 10.7692H15.9295C16.0536 10.7692 16.1538 10.669 16.1538 10.5449V6.95513C16.1538 6.83103 16.0536 6.73077 15.9295 6.73077H10.5449ZM8.97436 6.95513C8.97436 6.08802 9.67777 5.38462 10.5449 5.38462H15.9295C16.7979 5.38462 17.5 6.08846 17.5 6.95513V10.5449C17.5 11.4133 16.7961 12.1154 15.9295 12.1154H10.5449C9.67642 12.1154 8.97436 11.4115 8.97436 10.5449V6.95513ZM2.96153 14.5833C2.96153 14.2116 3.26287 13.9103 3.63461 13.9103H7.58333C7.95505 13.9103 8.25641 14.2116 8.25641 14.5833C8.25641 14.9551 7.95505 15.2564 7.58333 15.2564H3.63461C3.26287 15.2564 2.96153 14.9551 2.96153 14.5833ZM5.90529 7.43122C6.16821 7.16836 6.16832 6.74223 5.90553 6.47931C5.64275 6.21639 5.21658 6.21629 4.95366 6.47907L3.15879 8.27301C3.03253 8.39928 2.96157 8.57042 2.96153 8.74892V8.75C2.96153 8.94636 3.0456 9.12306 3.17972 9.2461L4.95342 11.0207C5.21621 11.2836 5.64238 11.2837 5.90529 11.021C6.16821 10.7581 6.16832 10.332 5.90553 10.0691L5.25988 9.42308H6.95512C7.32685 9.42308 7.62821 9.12172 7.62821 8.75C7.62821 8.37828 7.32685 8.07692 6.95512 8.07692H5.25923L5.90529 7.43122Z" fill="black"/>
                        </svg>
                    </i>
                </div>
                <div class="dashboard-list-user-content">
                    @if ($item->type == payment_gateway_const()::TYPEADDMONEY)
                    <h4 class="title">{{ __("Add Balance via") }} <span class="text--warning">{{ $item->gateway_currency->name }}</span></h4>
                    @elseif ($item->type == payment_gateway_const()::TYPEWITHDRAW)
                        <h4 class="title">{{ __("Withdraw Money via") }} <span class="text--warning">{{ $item->gateway_currency->name }}</span></h4>
                    @elseif ($item->type == payment_gateway_const()::TYPEMONEYEXCHANGE)
                    <h4 class="title">{{ __("Exchange Money") }} <span class="text--warning">{{ $item->request_currency }} To {{ $item->details->charges->exchange_currency }}</span></h4>
                    @elseif ($item->type == payment_gateway_const()::TYPETRANSFERMONEY)
                        @if ($item->isAuthUser()) 
                            <h4 class="title">{{ __("Send Money to ") }} <span class="text--warning">{{ $item->receiver_info->username }}</span></h4>
                        @else
                            <h4 class="title">{{ __("Received Money from ") }} <span class="text--warning">{{ $item->user->username }}</span></h4>
                        @endif
                    @elseif ($item->type == payment_gateway_const()::REQUESTMONEY)
                        @if ($item->user_id != auth()->user()->id)
                        <h4 class="title">{{ __("Request Money Payment Received") }}</h4>
                        @else
                        <h4 class="title">{{ __("Request Money Payment") }}</h4> 
                        @endif
                    
                    @elseif ($item->type == payment_gateway_const()::TYPEADDSUBTRACTBALANCE)
                    <h4 class="title">{{ __("Balance Update From Admin (".$item->request_currency.")") }} </h4>
                    @elseif ($item->type == payment_gateway_const()::REDEEMVOUCHER)
                    <h4 class="title">{{ __("Redeem Voucher") }} </h4>
                    @elseif ($item->type == payment_gateway_const()::TYPEMAKEPAYMENT)
                    <h4 class="title">{{ __("Make Payment Vai") }} Walletium </h4>
                    @elseif ($item->type == payment_gateway_const()::VIRTUALCARD)
                    <h4 class="title">{{ __($item->type) ?? '' }} <span>({{ $item->remark ?? '' }})</span></h4>
                    @endif
                    <span class="{{ $item->stringStatus->class }}">{{ $item->stringStatus->value }} </span>
                </div>
            </div>
        </div>
        <div class="dashboard-list-right">
            @if ($item->type == payment_gateway_const()::TYPEADDMONEY)
            <h4 class="main-money text--base">{{ get_amount($item->request_amount,$item->request_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->total_payable,$item->gateway_currency->currency_code) }}</h6>
            @elseif($item->type == payment_gateway_const()::TYPEWITHDRAW)
            <h4 class="main-money text--base"> {{ get_amount($item->total_payable,$item->gateway_currency->currency_code) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->request_amount,$item->request_currency) }}</h6>
            @elseif($item->type == payment_gateway_const()::TYPEMONEYEXCHANGE)
            <h4 class="main-money text--base"> {{ get_amount($item->receive_amount,$item->payment_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->request_amount,$item->request_currency) }}</h6>   
            @elseif ($item->type == payment_gateway_const()::TYPETRANSFERMONEY)
            <h4 class="main-money text--base"> {{ get_amount($item->receive_amount,$item->payment_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->request_amount,$item->user_wallet->currency->code) }}</h6>
            @elseif ($item->type == payment_gateway_const()::REQUESTMONEY)
            <h4 class="main-money text--base">{{ get_amount($item->request_amount,$item->request_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->total_payable,$item->payment_currency) }}</h6>
            @elseif ($item->type == payment_gateway_const()::TYPEADDSUBTRACTBALANCE)
            <h4 class="main-money text--base">{{ get_amount($item->request_amount,$item->request_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->available_balance,$item->request_currency) }}</h6>
            @elseif ($item->type == payment_gateway_const()::REDEEMVOUCHER)
            <h4 class="main-money text--base">{{ get_amount($item->request_amount,$item->request_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->total_payable,$item->request_currency) }}</h6>
            @elseif ($item->type == payment_gateway_const()::TYPEMAKEPAYMENT)
            <h4 class="main-money text--base">{{ get_amount($item->request_amount,$item->request_currency) }}</h4>
            <h6 class="exchange-money">{{ get_amount($item->total_payable,$item->request_currency) }}</h6>
            @elseif ($item->type == payment_gateway_const()::VIRTUALCARD)
            <h4 class="main-money text--base">{{ get_amount($item->total_payable,$item->details->charges->from_currency,3) }}</h4>
     
        @endif
        </div>
    </div>
    <div class="preview-list-wrapper">
        @if ($item->voucher != null)
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper">
                    <div class="preview-list-user-icon">
                        <i class="lab la-tumblr"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __('Voucher Code') }}</span>
                    </div>
                </div>
            </div>
            <div class="preview-list-right">
                <span>{{ $item->voucher->code }}</span>
            </div>
        </div> 
        @endif 
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper">
                    <div class="preview-list-user-icon">
                        <i class="lab la-tumblr"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __('Transaction ID') }}</span>
                    </div>
                </div>
            </div>
            <div class="preview-list-right">
                <span>{{ $item->trx_id }}</span>
            </div>
        </div>
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper">
                    <div class="preview-list-user-icon">
                        <i class="las la-exchange-alt"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __("Exchange Rate") }}</span>
                    </div>
                </div>
            </div>
            <div class="preview-list-right">
                @if ($item->type == payment_gateway_const()::TYPEADDMONEY || $item->type == payment_gateway_const()::TYPEWITHDRAW)
                <span>1 {{ $item->request_currency }} = {{ get_amount($item->exchange_rate,$item->gateway_currency->currency_code,3) }}</span> 
                @elseif ($item->type == payment_gateway_const()::TYPEMONEYEXCHANGE)
                <span>1 {{ $item->request_currency }} = {{ get_amount($item->exchange_rate,$item->details->charges->exchange_currency,3) }}</span> 
                @elseif ($item->type == payment_gateway_const()::TYPETRANSFERMONEY)
                <span>1 {{ $item->request_currency }} = {{ get_amount($item->exchange_rate,$item->payment_currency) }}</span>
                @elseif ($item->type == payment_gateway_const()::REQUESTMONEY)
                <span>1 {{ $item->request_currency }} = 1 {{ $item->payment_currency }}</span>
                @elseif ($item->type == payment_gateway_const()::TYPEADDSUBTRACTBALANCE)
                <span>1 {{ $item->request_currency }} = 1 {{ $item->request_currency }}</span>
                @elseif ($item->type == payment_gateway_const()::REDEEMVOUCHER)
                <span>1 {{ $item->request_currency }} = 1 {{ $item->request_currency }}</span>
                @elseif ($item->type == payment_gateway_const()::TYPEMAKEPAYMENT)
                <span>1 {{ $item->request_currency }} = 1 {{ $item->request_currency }}</span>
                @elseif ($item->type == payment_gateway_const()::VIRTUALCARD)
                <span>1 {{ $item->request_currency ?? '' }} = {{ get_amount($item->details->charges->exchange_rate,$item->details->charges->from_currency,3) }}</span>
                @endif
                
            </div>
        </div> 
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper">
                    <div class="preview-list-user-icon">
                        <i class="las la-battery-half"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __("Fees & Charge") }}</span>
                    </div>
                </div>
            </div>
            <div class="preview-list-right">
                @if ($item->type == payment_gateway_const()::TYPEADDMONEY || $item->type == payment_gateway_const()::TYPEWITHDRAW)
                <span>{{ @get_amount($item->total_charge,$item->gateway_currency->currency_code) }}</span>
                @elseif ($item->type == payment_gateway_const()::TYPEMONEYEXCHANGE)
                <span>{{ get_amount($item->total_charge,$item->request_currency) }}</span> 
                @elseif ($item->type == payment_gateway_const()::TYPETRANSFERMONEY)
                <span>{{ get_amount($item->total_charge,$item->request_currency) }}</span>
                @elseif ($item->type == payment_gateway_const()::REQUESTMONEY)
                <span>{{ get_amount($item->total_charge,$item->request_currency) }}</span>
                @elseif ($item->type == payment_gateway_const()::REDEEMVOUCHER)
                <span>{{ get_amount($item->total_charge,$item->request_currency) }}</span>
                @elseif ($item->type == payment_gateway_const()::TYPEADDSUBTRACTBALANCE)
                <span>0 {{ $item->request_currency }}</span>
                @elseif ($item->type == payment_gateway_const()::TYPEMAKEPAYMENT)
                <span>{{ get_amount($item->total_charge,$item->request_currency) }}</span>
                @elseif ($item->type == payment_gateway_const()::VIRTUALCARD)
                <span>{{ get_amount($item->total_charge,$item->details->charges->from_currency,8) }}</span>
                @endif
            </div>
        </div> 
        @if ($item->type == payment_gateway_const()::REQUESTMONEY)
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper"> 
                    <div class="preview-list-user-icon">
                        <i class="las la-user"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __('Paid By') }}</span>
                    </div>
                </div>
            </div> 
            <div class="preview-list-right">
                <span>{{ $item->user->email }}</span>
            </div>
        </div> 
        @endif
        @if ($item->remark != null)
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper"> 
                    <div class="preview-list-user-icon">
                        <i class="las la-smoking"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __('Remarks') }}</span>
                    </div>
                </div>
            </div> 
            <div class="preview-list-right">
                <span>{{ $item->remark }}</span>
            </div>
        </div> 
        @endif
        @if ($item->reject_reason != null)
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper"> 
                    <div class="preview-list-user-icon">
                        <i class="las la-smoking"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __('Reject Reason') }}</span>
                    </div>
                </div>
            </div> 
            <div class="preview-list-right">
                <span>{{ $item->reject_reason }}</span>
            </div>
        </div> 
        @endif
        <div class="preview-list-item">
            <div class="preview-list-left">
                <div class="preview-list-user-wrapper"> 
                    <div class="preview-list-user-icon">
                        <i class="las la-clock"></i>
                    </div>
                    <div class="preview-list-user-content">
                        <span>{{ __('Date') }}</span>
                    </div>
                </div>
            </div> 
            <div class="preview-list-right">
                <span>{{ $item->created_at }}</span>
            </div>
        </div> 
        @if ($item->type == payment_gateway_const()::TYPEADDMONEY)
        @if ($item->gateway_currency->gateway->isTatum($item->gateway_currency->gateway) && $item->status == payment_gateway_const()::STATUSWAITING)
            <div class="preview-list-item d-block">
                <div class="preview-list-left">
                    <div class="preview-list-user-wrapper">
                        <div class="preview-list-user-icon">
                            <i class="las la-times-circle"></i>
                        </div>
                        <div class="preview-list-user-content">
                            <span>{{ __("Txn Hash") }}</span>
                        </div>
                    </div>
                    <form action="{{ setRoute('user.add.money.payment.crypto.confirm', $item->trx_id) }}" method="POST">
                        @csrf
                        @php
                            $input_fields = $item->details->payment_info->requirements ?? [];
                        @endphp

                        @foreach ($input_fields as $input)
                            <div class="mt-2">
                                <input type="text" class="form-control" name="{{ $input->name }}" placeholder="{{ $input->placeholder ?? "" }}" required>
                            </div>
                        @endforeach

                        <div class="text-end">
                            <button type="submit" class="btn--base my-2">{{ __("Process") }}</button>
                        </div>

                    </form>
                </div>
            </div>
        @endif
        @endif
    </div>
</div>
   @empty
   <div class="alert alert-primary" style="margin-top: 37.5px; text-align:center">{{ __('No data found!') }}</div>
   @endforelse
   {{ get_paginate($transactions) }}
</div>