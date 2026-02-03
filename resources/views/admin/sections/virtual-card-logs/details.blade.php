@extends('admin.layouts.master')

@push('css')
    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,
        .fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view {
            height: 330px !important;
        }
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('admin.dashboard'),
            ],
        ],
        'active' => __($page_title),
    ])
@endsection

@section('content')
    <div class="row mb-30-none">

        <div class="col-lg-12 mb-30">
            <div class="transaction-area">
                <h4 class="title mb-0"><i class="fas fa-user text--base me-2"></i>{{ __('Card Information') }}</h4>
                <div class="content pt-0">
                    <div class="list-wrapper">
                        <ul class="list">
                            <li>{{ __('Card Holder Name') }}<span>{{ $transaction->details->card_info->card_name ?? '' }}</span>
                            </li>
                            <li>{{ __('Card Currency') }}<span>{{ $transaction->details->card_info->currency ?? '' }}</span>
                            </li>
                            <li>{{ __('ENV Type') }}<span>{{ $transaction->details->card_info->env ?? '' }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-30">
            <div class="transaction-area">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="title"><i class="fas fa-user text--base me-2"></i>{{ __('Payment Summary') }}</h4>
                </div>
                <div class="content pt-0">
                    <div class="list-wrapper">
                        <ul class="list">
                            <li>{{ __('Transaction ID') }} <span>{{ $transaction->trx_id ?? '' }}</span> </li>
                            <li>{{ __('Transaction Type') }} <span>{{ $transaction->type ?? '' }}</span> </li>

                            <li>{{ __('Amount') }}
                                <span>{{ get_amount($transaction->request_amount, $transaction->request_currency) }}</span>
                            </li>

                            <li>{{ __('Exchange Rate') }} <span>1 {{ $transaction->request_currency }} =
                                    {{ get_amount($transaction->exchange_rate, $transaction->payment_currency, 'double') }}</span>
                            </li>
                            <li>{{ __('Total Charge') }}
                                @if ($transaction->type == transaction_const()::TYPE_CARD_WITHDRAW)
                                    <span>{{ get_amount($transaction->total_charge, $transaction->request_currency, get_wallet_precision()) }}</span>
                                @else
                                    <span>{{ get_amount($transaction->total_charge, $transaction->payment_currency, get_wallet_precision()) }}</span>
                                @endif
                            </li>
                            @if ($transaction->type == transaction_const()::TYPE_CARD_WITHDRAW)
                                <li>{{ __('Will Get') }}
                                    <span>{{ get_amount($transaction->receive_amount, $transaction->payment_currency, get_wallet_precision()) }}</span>
                                </li>
                            @endif

                            <li>{{ __('Total Payable') }}
                                @if ($transaction->type == transaction_const()::TYPE_CARD_WITHDRAW)
                                    <span>{{ get_amount($transaction->total_payable, $transaction->request_currency, get_wallet_precision()) }}</span>
                                @else
                                    <span>{{ get_amount($transaction->total_payable, $transaction->payment_currency, get_wallet_precision()) }}</span>
                                @endif
                            </li>

                            <li class="five">{{ __('Status:') }} <span
                                    class="{{ $transaction->StringStatus->class }}">{{ $transaction->StringStatus->value }}</span>
                            </li>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
