@extends('admin.layouts.master')

@push('css')

@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __("Virtual Card Api")])
@endsection

@section('content')
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __("Virtual Card Api") }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.virtual.card.api.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row mb-10-none">
                    <div class="col-xl-12 col-lg-12 form-group">
                        <label>{{ __("Name") }}*</label>
                        <select class="form--control nice-select" name="api_method">
                            <option disabled>{{ __("Select Platfrom") }}</option>
                            <option value="cardyfie" @if(@$api->config->name == 'cardyfie') selected @endif>@lang('Cardyfie')</option>
                        </select>
                    </div>
                     <div class="col-xl-12 col-lg-12 form-group" id="cardyfie">
                        <div class="row" >
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Public Key") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-key"></i></span>
                                    <input type="text" class="form--control" name="cardyfie_public_key" value="{{ @$api->config->cardyfie_public_key }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Secret Key") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-key"></i></span>
                                    <input type="text" class="form--control" name="cardyfie_secret_key" value="{{ @$api->config->cardyfie_secret_key }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Sandbox URL") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-link"></i></span>
                                    <input type="text" class="form--control" name="cardyfie_sandbox_url" value="{{ @$api->config->cardyfie_sandbox_url ?? '' }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Production URL") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-link"></i></span>
                                    <input type="text" class="form--control" name="cardyfie_production_url" value="{{ @$api->config->cardyfie_production_url ?? '' }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Webhook Secret") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-key"></i></span>
                                    <input type="text" class="form--control" name="cardyfie_webhook_secret" value="{{ @$api->config->cardyfie_webhook_secret ?? '' }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Webhook URL") }}</label>
                                <div class="input-group append">
                                    <span class="input-group-text"><i class="las la-link"></i></span>
                                    {{-- <input type="text" class="form--control referralURL"  value="{{ setRoute('user.cardyfie.virtual.card.webhook') }}" readonly> --}}
                                    <input type="text" class="form--control referralURL"  value="{{ route('user.dashboard') }}" readonly>
                                    <div class="input-group-text copytext" id="copyBoard"><i class="las la-copy"></i></div>
                                </div>
                            </div>

                             <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-sm-12 form-group">
                                <label>{{ __("Universal Card Issue Fee") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_universal_card_issues_fee" value="{{ get_amount($api->config->cardyfie_universal_card_issues_fee ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>

                             <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-sm-12 form-group">
                                <label>{{ __("Platinum Card Issue Fee") }}*</label>
                                <div class="input-group append">
                                    <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_platinum_card_issues_fee" value="{{ get_amount($api->config->cardyfie_platinum_card_issues_fee ?? 0,null,get_wallet_precision()) }}">

                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-sm-12 form-group">
                                <label>{{ __("Card Deposit Fixed Fee") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_card_deposit_fixed_fee" value="{{ get_amount($api->config->cardyfie_card_deposit_fixed_fee ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-sm-12 form-group">
                                <label>{{ __("Card Withdraw Fixed Fee") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_card_withdraw_fixed_fee" value="{{ get_amount($api->config->cardyfie_card_withdraw_fixed_fee ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Card Maintenance Fixed Fee") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_card_maintenance_fixed_fee" value="{{ get_amount($api->config->cardyfie_card_maintenance_fixed_fee ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Minimum Amount") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_min_limit" value="{{ get_amount($api->config->cardyfie_min_limit ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Maximum Amount") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_max_limit" value="{{ get_amount($api->config->cardyfie_max_limit ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Daily Limit") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_daily_limit" value="{{ get_amount($api->config->cardyfie_daily_limit ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 form-group">
                                <label>{{ __("Monthly Limit") }}*</label>
                                <div class="input-group append">
                                   <span class="input-group-text">{{ get_default_currency_code() }}</span>
                                    <input type="text" class="form--control number-input" name="cardyfie_monthly_limit" value="{{ get_amount($api->config->cardyfie_monthly_limit ?? 0,null,get_wallet_precision()) }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-sm-12 form-group">
                                @include('admin.components.form.switcher', [
                                    'label'         => __('Mode'),
                                    'value'         => old('cardyfie_mode',@$api->config->cardyfie_mode),
                                    'name'          => "cardyfie_mode",
                                    'options'       => [__('Live') => global_const()::LIVE,__('Sandbox') => global_const()::SANDBOX]
                                ])
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.input',[
                            'label'         =>__( 'Card Limit Within (1 to 3)'),
                            'name'          => 'card_limit',
                            'value'         => old('card_limit',@$api->card_limit),
                            'placeholder'   => "Enter 1-3 Only."
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.input-text-rich',[
                            'label'         => __('Card Details').'*',
                            'name'          => 'card_details',
                            'value'         => old('card_details',@$api->card_details),
                            'placeholder'   => "Write Here...",
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        <label for="card-image">{{ __("Background Image") }}</label>
                        <div class="col-12 col-sm-6 m-auto">
                            @include('admin.components.form.input-file',[
                                'label'         => false,
                                'class'         => "file-holder m-auto",
                                'old_files_path'    => files_asset_path('card-api'),
                                'name'          => "image",
                                'old_files'         => old('image',@$api->image)
                            ])
                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("Update"),
                            'permission'    => "admin.virtual.card.api.update"
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        (function ($) {
            "use strict";
            var method = '{{ @$api->config->name}}';
            if (!method) {
                method = 'flutterwave';
            }

            apiMethod(method);
            $('select[name=api_method]').on('change', function() {
                var method = $(this).val();
                apiMethod(method);
            });

            function apiMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'other') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

        })(jQuery);

    </script>
    <script>
             $('.copytext').on('click', function () {
                var copyText = $(this).closest('.input-group').find('.referralURL')[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");

                throwMessage('success', ['{{ "Copy Successfully" }}']);
            });
    </script>
@endpush
