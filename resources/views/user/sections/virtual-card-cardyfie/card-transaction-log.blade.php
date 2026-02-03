@extends('user.layouts.master')

@push('css')
<style>
    .dashboard-list-item.sent {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        'active' => __('Card Transaction Log'),
    ])
@endsection

@section('content')
<div class="dashboard-list-area mt-20">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __('Webhook logs') }}</h4>
    </div>
    @if(isset($get_card_transactions))
        @foreach($get_card_transactions ?? [] as $key => $value)
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item sent">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title"> {{ @$value['trx_type'] }}</h4>
                                <span class="sub-title text--danger"> <span class="badge badge--success ms-2">{{ __(@$value['status']) }}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-list-right">
                        <h4 class="main-money text--base">{{ get_amount($value['enter_amount'] ?? 0, $value['card_currency'],8 )  }} </h4>
                        <h6 class="exchange-money">{{ date("M-d-Y",strtotime($value['created_at'] ?? '')) }}</h6>
                    </div>
                </div>
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-keyboard"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ @$value['trx_type'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ __(@$value['status']) }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-coins"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __("Trx ID") }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ @$value['trx_id'] }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-network-wired"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{__("Amount Type")}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ @$value['amount_type'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
    <div class="alert alert-primary text-center">
        {{ __("No Record Found!") }}
    </div>
    @endif
</div>
@endsection
@push('script')
@endpush
