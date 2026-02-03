<div class="dashboard-area mt-10">
    <div class="dashboard-header-wrapper">
        <h4 class="title">{{ __('My Cards') }} </h4>
        <div class="dashboard-btn-wrapper">
            <div class="dashboard-btn">
                <a href="{{ setRoute('user.cardyfie.virtual.card.edit.customer') }}"
                    class="btn--base">{{ __('Update Customer') }}</a>
            </div>
        </div>
    </div>
    <div class="dashboard-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wigets-wrapper">
                    <div class="my-cards-widgets">
                        <div class="widget-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="card-title-content">
                                    <div class="card-title">{{ __('Total Card Balance') }}</div>
                                    <div class="card-description">{{ __('Your current available balance') }}</div>
                                </div>
                            </div>
                            <div class="card-value">
                                {{ get_amount($total_amount_of_cards->total_amount, $total_amount_of_cards->currency, 2) }}
                            </div>
                        </div>

                        <div class="widget-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="card-title-content">
                                    <div class="card-title">{{ __('Total Cards') }}</div>
                                    <div class="card-description">{{ __('Total number of active cards') }}</div>
                                </div>
                            </div>
                            <div class="card-value">{{ $total_cards }}</div>
                        </div>

                        <div class="widget-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true" data-slot="icon" class="sm:size-5 size-4">
                                        <path fill-rule="evenodd"
                                            d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z"
                                            clip-rule="evenodd"></path>
                                        <path fill-rule="evenodd"
                                            d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="card-title-content">
                                    <div class="card-title">{{ __('Active Cards') }}</div>
                                    <div class="card-description">{{ __('Currently active cards') }}</div>
                                </div>
                            </div>
                            <div class="card-value">{{ $total_enabled_cards }}</div>
                        </div>

                        <div class="widget-card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true" data-slot="icon" class="sm:size-5 size-4">
                                        <path
                                            d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z">
                                        </path>
                                        <path fill-rule="evenodd"
                                            d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.133 2.845a.75.75 0 0 1 1.06 0l1.72 1.72 1.72-1.72a.75.75 0 1 1 1.06 1.06l-1.72 1.72 1.72 1.72a.75.75 0 1 1-1.06 1.06L12 15.685l-1.72 1.72a.75.75 0 1 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="card-title-content">
                                    <div class="card-title">{{ __('Freezed Cards') }}</div>
                                    <div class="card-description">{{ __('Currently freezed cards') }}</div>
                                </div>
                            </div>
                            <div class="card-value">{{ $total_freezed_cards }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Available cards section -->
        <div class="available-cards-section">
            <div class="row">
                <div class="col-xl-12">
                    <div class="cc-section-header my-3">
                        <div>
                            <div class="cc-section-title">{{ __('Available Cards') }}</div>
                            <p class="cc-section-subtitle">
                                {{ __('This chart represents the total amount withdrawn across the selected months.') }}
                            </p>
                        </div>
                        <div class="dashboard-btn-wrapper">
                            <div class="dashboard-btn">
                                <a href="{{ route('user.cardyfie.virtual.card.create') }}"
                                    class="btn--base">{{ __('Create Cards') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-cards-wrapper">
                    <div class="row">
                        @forelse ($customer_cards ?? [] as $card)
                            <div class=" col-xxl-3 col-xl-4 col-lg-4  col-md-5 col-sm-8 col-12 g-3 gy-4 ">
                                <div class="user-vc-card {{ $card->card_tier }}"
                                    @if ($card->card_tier == 'universal') style="background-image: url({{ get_image($card_api->universal_image ?? '', 'card-api') }})" @else style="background-image: url({{ get_image($card_api->platinum_image ?? '', 'card-api') }})" @endif>
                                    <div class="user-card-item card-details" data-card-id="{{ $card->ulid }}"
                                        onclick="openModal()">
                                        <div class="user-card-body">
                                            <div class="user-card-header">
                                                <img class="user-card-logo" src="{{ get_fav($basic_settings) ?? '' }}"
                                                    alt="logo">
                                                <div class="user-card-status">
                                                    @if ($card->status == 'ENABLED')
                                                        <div class="status-active"></div>
                                                        <span class="status-text text-white">{{ __('Active') }}</span>
                                                    @elseif ($card->status == 'CLOSED')
                                                        <div class="status-inactive"></div>
                                                        <span class="status-text text-white">{{ __('Closed') }}</span>
                                                    @else
                                                        <div class="status-freeze"></div>
                                                        <span class="status-text text-white">{{ __('Freezed') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="user-card-number">{{ $card->masked_pan }}</div>
                                            <div class="user-card-name">{{ $card->card_name }}</div>
                                            <div class="user-card-footer">
                                                <div class="user-card-balance">
                                                    {{ get_amount($card->amount, $card->currency, 'double') }}</div>
                                                @if ($card->card_type == 'visa')
                                                    <img class="user-card-brand"
                                                        src="{{ asset('frontend/images/card/visa-svg.svg') }}"
                                                        alt="">
                                                @else
                                                    <img class="user-card-brand"
                                                        src="{{ asset('frontend/images/card/mastercard-svg.svg') }}"
                                                        alt="">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="alert alert-primary text-center">
                                {{ __('No Card Created Yet!') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{ get_paginate($customer_cards) }}
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start saved card MODAL- CARD DETAILS
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="saved-cards-modal">
    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay">
        <!-- Card Info Modal -->
        <div id="card-info-modal">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title-section">
                    <div class="modal-icon">💳</div>
                    <div class="modal-title-text">
                        <h3>{{ __('Card Information') }}</h3>
                        <p>{{ __('Move funds within your card, freeze or close it') }}</p>
                    </div>
                </div>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>

            <!-- Modal Content -->
            <div class="modal-content d-flex ">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6">
                        <!-- Card Section -->
                        <div class="card-section"
                            style="background-image: url({{ asset('backend/images/card-settings/universal.png') }})">
                            <div class="modal-card card-loading d-flex justify-content-center align-items-center"
                                style="height: 250px">
                                <div class="spinner-border text-white" role="status">
                                    <span class="sr-only">{{ __('Loading') }}...</span>
                                </div>
                            </div>
                            <div class="modal-card card-show d-none">
                                <div class="modal-card-header">
                                    <img class="logo" src="{{ get_fav($basic_settings) ?? '' }}" alt="">
                                    <div class="card-status">
                                        <div class="status-dot"></div>
                                        <span class="status-text text-white">{{ __('Enabled') }}</span>
                                    </div>
                                </div>

                                <div class="card-number-section">
                                    <span class="card-number" id="cardNumber"></span>
                                    <button class="copy-btn" onclick="copyCardNumber()" title="Copy card number">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>

                                <div class="cardholder-name text-capitalize" id="holderName"></div>

                                <div class="card-info-row">
                                    <div class="info-group">
                                        <span class="info-label">{{ __('EXP') }}</span>
                                        <div class="info-value-container">
                                            <span class="info-value text-white" id="expiryDate"></span>
                                            <button class="copy-btn" onclick="copyExpiry()" title="Copy expiry">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="info-group">
                                        <span class="info-label">{{ __('CVV') }}</span>
                                        <div class="info-value-container">
                                            <span class="info-value text-white" id="cvvNumber"></span>
                                            <button class="copy-btn" onclick="copyCVV()" title="Copy CVV">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-brand-container">
                                        <img class="card-brand"
                                            src="{{ asset('frontend/images/card/mastercard-svg.svg') }}"
                                            alt="VISA logo">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div class="account-info">
                            <div class="info-item">
                                <div class="info-item-label">{{ __('Balance') }}</div>
                                <div class="info-item-value balance-value" id="cardBalance">{{ __('Loading...') }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-item-label">{{ __('Card Type') }}</div>
                                <div class="info-item-value balance-value" id="cardTypeOnModal">
                                    {{ __('Loading...') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6">
                        <!-- Billing Address -->
                        <div class="billing-section">
                            <div class="billing-header">
                                <h4 class="billing-title">{{ __('Billing Address') }}</h4>
                            </div>
                            <div class="billing-address">
                                <div class="billing-adress-wrapper" id="billingAddress">
                                    <p>{{ __('Loading') }}</p>
                                </div>
                                <div class="billing-address-copy-icon">
                                    <button class="copy-btn" onclick="copyBillingAddress()"
                                        title="Copy billing address">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons" id="transaction-loading">

                        </div>

                        <div class="action-buttons" id="deposit-loading">

                        </div>

                        <!-- Bottom Actions -->
                        <div class="bottom-actions">
                            <div class="withdraw-loading">

                            </div>

                            <button class="freeze-btn freeze-loading">
                                {{ __('Loading') }}
                            </button>
                            <button class="freeze-btn freeze-workable d-none" id="freezeBtn">
                                {{ __('Freeze') }}
                                <i class="fas fa-eraser"></i>
                            </button>

                        </div>

                        <!-- Close Card -->
                        <div class="close-card-section" id="close-loading">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Notification -->
    <div class="copy-notification" id="copyNotification">
        {{ __('Copied to clipboard') }}!
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End SAVED CARD MODAL - CARD DETAILS
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@push('script')
@endpush
