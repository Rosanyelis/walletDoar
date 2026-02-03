@extends('admin.layouts.master')
@push('css')
    <style>
        .fileholder {
            min-height: 200px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,
        .fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view {
            height: 156px !important;
        }

        .payment-gateway-currencies-wrapper {
            display: none;
            /* Hidden by default */
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
        'active' => __('Provider'),
    ])
@endsection

@section('content')
    <form action="{{ route('admin.virtual.card.provider.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="custom-card credentials">
            <div class="card-header">
                <div class="row d-flex justify-content-between w-100">
                    <div class="col-lg-6">
                        <h6 class="title">{{ __('Update Provider') }} : {{ $provider->name }}</h6>
                    </div>
                    <div class="col-lg-6 text-end">
                        <a href="{{ url('https://cardyfie.com/') }}" target="_blank">
                            <small>
                                {{ __('Learn More About Cardyfie') }}
                            </small>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-10-none">
                    <div class="col-xl-3 col-lg-3 form-group">
                        <input type="hidden" name="id" value="{{ $provider->id }}">
                        <input type="hidden" name="api_method" value="{{ $provider->provider_slug }}">
                        @include('admin.components.form.input-file', [
                            'label' => __('Provider Image'),
                            'name' => 'provider_image',
                            'class' => 'file-holder',
                            'old_files_path' => files_asset_path('card-providers-images'),
                            'old_files' => old('provider_image', $provider->provider_image),
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="gateway-content">
                            <h3 class="title">{{ $provider->provider_title }}</h3>
                            <p>{{ __('Global Setting for') }} {{ $provider->title }} {{ __('in bellow') }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Public Key') }}</label>
                            <input type="text" class="form--control" name="public_key"
                                value="{{ $provider->config->public_key }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Secret Key') }}</label>
                            <input type="text" class="form--control" name="secret_key"
                                value="{{ $provider->config->secret_key }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Sandbox Url') }}</label>
                            <input type="text" class="form--control" name="sandbox_url"
                                value="{{ $provider->config->sandbox_url }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Production Url') }}</label>
                            <input type="text" class="form--control" name="production_url"
                                value="{{ $provider->config->production_url }}">
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                            <label>{{ __('Webhook Secret') }}*</label>
                            <input type="text" class="form--control" name="webhook_secret"
                                value="{{ $provider->config->webhook_secret }}">
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-group">
                            <label>{{ __('Webhook URL') }}</label>
                            <input type="text" class="form--control referralURL" value="{{ setRoute('user.cardyfie.virtual.card.webhook') }}" readonly>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label>{{ __('Card Details') }}</label>
                            <textarea name="card_details" class="rich-text-editor form--control d-none">{!! $provider->card_details ?? '' !!}</textarea>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            @include('admin.components.form.switcher', [
                                'label' => __('Provider Environment'),
                                'value' => old('mode', $provider->config->mode),
                                'name' => 'mode',
                                'options' => [
                                    __('Live') => global_const()::LIVE,
                                    __('Sandbox') => global_const()::SANDBOX,
                                ],
                            ])
                        </div>

                        <div class="col-6 col-md-6 form-group">
                            <label for="card-image">{{ __('Universal Card Image') }}*</label>
                            @include('admin.components.form.input-file', [
                                'label' => false,
                                'class' => 'file-holder',
                                'name' => 'universal_image',
                                'old_files_path' => files_asset_path('card-api'),
                                'old_files' => old('universal_image', $provider->universal_image),
                            ])
                        </div>
                        <div class="col-6 col-md-6 form-group">
                            <label for="card-image">{{ __('Platinum Card Image') }}*</label>
                            @include('admin.components.form.input-file', [
                                'label' => false,
                                'class' => 'file-holder',
                                'name' => 'platinum_image',
                                'old_files_path' => files_asset_path('card-api'),
                                'old_files' => old('platinum_image', $provider->platinum_image),
                            ])
                        </div>

                    </div>
                    <div class="col-xl-3 col-lg-3 form-group">
                        @isset($provider)
                            <div class="gateway-content text-end">
                                <h5 class="title">{{ __('Total Supported Currency') }}</h5>
                            </div>
                            <div class="custom-checkbox-area">
                                @foreach ($provider->supported_currencies as $item)
                                    <div class="custom-check-group two">
                                        <input type="checkbox" id="{{ Str::lower($item) }}" class="payment-gateway-currency"
                                            name="currency[]" value="{{ $item }}" data-provider="{{ $provider->id }}"
                                            data-currency-code="{{ $item }}"
                                            @if ($provider->currencies->where('currency_code', $item)->first()->status ?? 0 == 1) checked @endif>
                                        <label for="{{ Str::lower($item) }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endisset
                    </div>
                    <div class="custom-card mt-15">
                        <div class="card-body">
                            <div class="row mb-10-none">
                                <div class="col-xl-12 col-lg-12 form-group">
                                    @include('admin.components.button.form-btn', [
                                        'class' => 'w-100 btn-loading',
                                        'text' => 'Update',
                                        'permission' => 'admin.payment.gateway.update',
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @isset($provider)
        <div class="payment-gateway-currencies-wrapper">
            @foreach ($provider->currencies as $item)
                <form action="{{ route('admin.virtual.card.providers.currency.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <div class="custom-card mt-15 gateway-currency">
                        <div class="card-header">
                            <h6 class="currency-title">{{ $provider->provider_title }} {{ $item->currency_code }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-lg-6 mb-10">
                                    <div class="custom-inner-card">
                                        <div class="card-inner-header">
                                            <h5 class="title">{{ __('Amount Limit') }}</h5>
                                        </div>
                                        <div class="card-inner-body">
                                            <div class="row">
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label for="min_limit">{{ __('Min Limit') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}" id="min_limit"
                                                                name="min_limit" class="form--control number-input"
                                                                value="{{ get_amount($item->min_limit, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">{{ $item->currency_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label for="max_limit">{{ __('Max Limit') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}" id="max_limit"
                                                                name="max_limit" class="form--control number-input"
                                                                value="{{ get_amount($item->max_limit, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">{{ $item->currency_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-xl-4 col-lg-6 mb-10">
                                    <div class="custom-inner-card">
                                        <div class="card-inner-header">
                                            <h5 class="title">{{ __('Exchange Rate') }}</h5>
                                        </div>
                                        <div class="card-inner-body">
                                            <div class="row">
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label>{{ __('Rate') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text append ">1 &nbsp; <span
                                                                    class="default-currency">{{ get_default_currency_code($default_currency) }}</span>
                                                                = </span>
                                                            <input type="text" class="form--control number-input"
                                                                value="{{ get_amount($item->rate, null, 2) }}"
                                                                name="exchange_rate">
                                                            <span
                                                                class="input-group-text currency">{{ $item->currency_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label>{{ __('Symbol') }}</label>
                                                        <input type="text" class="form--control"
                                                            value="{{ $item->currency_symbol }}" name="symbol">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-xl-4 col-lg-6 mb-10">
                                    <div class="custom-inner-card">
                                        <div class="card-inner-header">
                                            <h5 class="title">{{ __('Provider Fee') }}</h5>
                                        </div>
                                        <div class="card-inner-body">
                                            <div class="row">
                                                <div class="col-xl-12 form-group">
                                                    <label
                                                        for="cardyfie_universal_card_issues_fee">{{ __('Universal Card Issue Fee') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" placeholder="{{ __("Write Here") }}"
                                                            id="cardyfie_universal_card_issues_fee"
                                                            name="cardyfie_universal_card_issues_fee"
                                                            class="form--control number-input"
                                                            value="{{ get_amount($item->fees->cardyfie_universal_card_issues_fee, null, 2) }}">
                                                        <span
                                                            class="input-group-text currency">{{ $item->currency_code }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <label
                                                        for="cardyfie_platinum_card_issues_fee">{{ __('Platinum Card Issue Fee') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" placeholder="{{ __("Write Here") }}"
                                                            id="cardyfie_platinum_card_issues_fee"
                                                            name="cardyfie_platinum_card_issues_fee"
                                                            class="form--control number-input"
                                                            value="{{ get_amount($item->fees->cardyfie_platinum_card_issues_fee, null, 2) }}">
                                                        <span
                                                            class="input-group-text currency">{{ $item->currency_code }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label
                                                            for="cardyfie_card_deposit_fixed_fee">{{ __('Card Deposit Fixed Fee') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}"
                                                                id="cardyfie_card_deposit_fixed_fee"
                                                                name="cardyfie_card_deposit_fixed_fee"
                                                                class="form--control number-input"
                                                                value="{{ get_amount($item->fees->cardyfie_card_deposit_fixed_fee, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">{{ $item->currency_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label
                                                            for="cardyfie_card_deposit_percent_fee">{{ __('Card Deposit Percent Fee') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}"
                                                                id="cardyfie_card_deposit_percent_fee"
                                                                name="cardyfie_card_deposit_percent_fee"
                                                                class="form--control number-input"
                                                                value="{{ get_amount($item->fees->cardyfie_card_deposit_percent_fee, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label
                                                            for="cardyfie_card_withdraw_fixed_fee">{{ __('Card Withdraw Fixed Fee') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}"
                                                                id="cardyfie_card_withdraw_fixed_fee"
                                                                name="cardyfie_card_withdraw_fixed_fee"
                                                                class="form--control number-input"
                                                                value="{{ get_amount($item->fees->cardyfie_card_withdraw_fixed_fee, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">{{ $item->currency_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label
                                                            for="cardyfie_card_withdraw_percent_fee">{{ __('Card Withdraw Percent Fee') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}"
                                                                id="cardyfie_card_withdraw_percent_fee"
                                                                name="cardyfie_card_withdraw_percent_fee"
                                                                class="form--control number-input"
                                                                value="{{ get_amount($item->fees->cardyfie_card_withdraw_percent_fee, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <div class="form-group">
                                                        <label
                                                            for="cardyfie_card_maintenance_fixed_fee">{{ __('Card Maintenance Fixed Fee') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" placeholder="{{ __("Write Here") }}"
                                                                id="cardyfie_card_maintenance_fixed_fee"
                                                                name="cardyfie_card_maintenance_fixed_fee"
                                                                class="form--control number-input"
                                                                value="{{ get_amount($item->fees->cardyfie_card_maintenance_fixed_fee, null, 2) }}">
                                                            <span
                                                                class="input-group-text currency">{{ $item->currency_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.button.form-btn', [
                                    'class' => 'w-100 btn-loading',
                                    'text' => 'Update',
                                    'permission' => 'admin.payment.gateway.update',
                                ])
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    @endisset


@endsection

@push('script')
    <script>
        // Function to check if any currency checkbox is checked
        function checkAnyCurrencySelected() {
            return $('.payment-gateway-currency:checked').length > 0;
        }

        // Function to toggle currencies wrapper visibility
        function toggleCurrenciesWrapper() {
            if (checkAnyCurrencySelected()) {
                $('.payment-gateway-currencies-wrapper').show();
            } else {
                $('.payment-gateway-currencies-wrapper').hide();
            }
        }

        // Initial check on page load
        $(document).ready(function() {
            toggleCurrenciesWrapper();
        });

        $(document).on('change', '.payment-gateway-currency', function() {
            let isChecked = $(this).is(':checked');
            let providerId = $(this).data('provider');
            let currencyCode = $(this).data('currency-code');

            if (isChecked) {
                // If checked, show the wrapper immediately
                $('.payment-gateway-currencies-wrapper').show();
            } else {
                var alertHtmlMarkup = getHtmlMarkup().modal_default_alert;
                var alertMessage = "Are you sure to remove <strong>" + currencyCode + "</strong> ?";
                var alertHtmlMarkup = replaceText(alertHtmlMarkup, alertMessage);
                openModalByContent({
                    content: alertHtmlMarkup,
                });
                $(".alert-submit-btn").addClass("gateway-remove-btn");
                btnLoadingRefresh();

                $(".gateway-remove-btn").click(function() {

                    $.ajax({
                        url: "{{ route('admin.virtual.card.providers.currency.status.update') }}",
                        method: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}",
                            provider_id: providerId,
                            currency_code: currencyCode,
                            status: isChecked ? 1 : 0
                        },
                        success: function(response) {
                            console.log(response.message);
                            $(this).prop("checked", false);
                            currentModalClose();
                            // Check if any checkboxes are still checked
                            if (!checkAnyCurrencySelected()) {
                                $('.payment-gateway-currencies-wrapper').hide();
                            }
                            throwMessage('success', response.message.success);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            throwMessage('success', response.message.error);
                        }
                    })
                });

            }
        });
    </script>
@endpush
