@extends('user.layouts.master')
@section('breadcrumb')
    @include('user.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("user.dashboard"),
        ]
    ], 'active' => __("KYC Verification")])
@endsection
@section('content')
<section class="account-section kyc-verify-section ptb-80">
    <div class="container">
        <div class="kyc-verify-card">
            @if (auth()->user()->kyc_verified == global_const()::PENDING)
                @include('user.auth.authorize.partials.kyc-status-card', ['status' => 'pending'])
            @elseif (auth()->user()->kyc_verified == global_const()::REJECTED)
                <h3 class="kyc-section-title mb-3">{{ __("KYC Verification") }} <span class="kyc-status-badge {{ auth()->user()->kycStringStatus->class ?? '' }}">{{ auth()->user()->kycStringStatus->value ?? '' }}</span></h3>
                <div class="kyc-rejected-box">
                    <strong>{{ __("Your KYC information is rejected.") }}</strong>
                    <div class="kyc-rejected-reason">{{ auth()->user()->kyc->reject_reason ?? "" }}</div>
                </div>
                <p class="kyc-section-desc mb-4">{{ __("Please resubmit your KYC information with valid data.") }}</p>
                @include('user.auth.authorize.partials.kyc-form-figma', ['kyc_fields' => $kyc_fields])
            @elseif (auth()->user()->kyc_verified == global_const()::APPROVED)
                @include('user.auth.authorize.partials.kyc-status-card', ['status' => 'approved'])
                
            @else
                <h3 class="kyc-section-title mb-3">{{ __("KYC Verification") }} <span class="kyc-status-badge {{ auth()->user()->kycStringStatus->class ?? '' }}">{{ auth()->user()->kycStringStatus->value ?? '' }}</span></h3>
                <p class="kyc-section-desc mb-4">{{ __("Please submit your KYC information with valid data.") }}</p>
                @include('user.auth.authorize.partials.kyc-form-figma', ['kyc_fields' => $kyc_fields])
            @endif

            <p class="kyc-back-link">{{ __("Back to ") }}<a href="{{ setRoute('user.dashboard') }}">{{ __("Dashboard") }}</a></p>
        </div>
    </div>
</section>
@endsection
