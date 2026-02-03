@extends('user.layouts.master')

@push('css')
    <style>
        .process-step.disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .process-step.disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .add-cards-section-wrapper .card-balance {
            font-size: 20px;
            font-weight: 700;
            text-align: right !important;
        }

        .add-cards-section-wrapper .card-number-text {
            font-size: 16px !important;
            font-weight: 600;
            margin-bottom: 4px;
            color: white;
        }

        .saved-cards-container .card-container {
            width: auto;
            min-width: 280px;
            max-width: 280px !important;
            height: 200px;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            -webkit-transition: -webkit-transform 0.3s ease;
            transition: -webkit-transform 0.3s ease;
            transition: transform 0.3s ease;
            transition: transform 0.3s ease, -webkit-transform 0.3s ease;
        }

        .nice-select:after {
            display: none !important;
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
        'active' => __('Create Card'),
    ])
@endsection

@section('content')
    <div class="dashboard-area mt-10">
        <div class="dashboard-header-wrapper">
            <h3>{{ __('Create Your Card') }}</h3>
        </div>
        <div class="create-card-section-wrapper ptb-30">
            <div class="card-process-container">
                <!-- Process Header -->
                <div class="process-header" id="processHeader">
                    <h2 class="process-title">{{ __('Start to Create Your Card') }}</h2>
                </div>
                <!-- Process Body -->
                <div class="process-body">
                    <!-- Left Side Steps -->
                    <div class="steps-sidebar">
                        <div class="steps-sidebar-wrapper">
                            <div class="step-item active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-info">
                                    <h4>{{ __('Choose Card Type') }}</h4>
                                    <p>{{ __('Select your preferred crypto card') }}</p>
                                </div>
                            </div>
                            <div class="step-item" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-info">
                                    <h4>{{ __('Complete Request') }}</h4>
                                    <p>{{ __('Fill in your card details') }}</p>
                                </div>
                            </div>
                            <div class="step-item" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-info">
                                    <h4>{{ __('View Request') }}</h4>
                                    <p>{{ __('Review your card application') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Content -->
                    <div class="content-area">
                        <!-- Step 1 Content -->
                        <div class="step-content active" id="step1">
                            <div class="card-processor-step">
                                <div class="card-selection-container">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <!-- Universal Card -->
                                            <div class="card-option card-type-container selected universal-card"
                                                data-card-type="universal">
                                                <div class="row g-4 justify-content-center">
                                                    <!-- Left Side - Card Info -->
                                                    <div class="col-xl-10 col-md-10">
                                                        <div class="card-info-content">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="card-radio me-3">
                                                                    <input type="radio" name="cardType"
                                                                        id="card-universal" value="universal" checked>
                                                                    <span class="radio-custom"></span>
                                                                </div>
                                                                <div>
                                                                    <h4 class="mb-2">{{ __('Universal Card') }}</h4>
                                                                    <p class="text-muted mb-3">
                                                                        {{ __('Perfect for freelancers, digital professionals, or anyone who needs smarter money tools.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Right Side - Card Preview -->
                                                    <div class="col-xl-10 col-md-10">
                                                        <div class="card-preview-wrapper">
                                                            <div class="card-preview">
                                                                <div class="card-content"
                                                                    style="background-image: url({{ get_image($card_api->universal_image ?? '', 'card-api') }})">
                                                                    <!-- Logo in top left corner -->
                                                                    <div class="card-logo">
                                                                        <img src="{{ get_fav($basic_settings) }}"
                                                                            alt="Logo">
                                                                    </div>
                                                                    <div class="card-number" id="previewCardNumber">**** ---
                                                                        ****</div>
                                                                    <div class="card-holder" id="previewCardHolder">
                                                                        {{ __('Card Holder Name') }}</div>
                                                                    <div class="card-balance balance-preview"
                                                                        id="previewBalance">--</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Details Section -->
                                                    <div class="col-xl-10 col-md-10 col-12">
                                                        <div class="card-details">
                                                            <button class="details-toggle" type="button">
                                                                {{ __('View Details') }}
                                                                <span class="toggle-arrow">↓</span>
                                                            </button>
                                                            <div class="details-content">
                                                                <div class="details-tags">
                                                                    <span class="tag">{{ __('Advertising') }}</span>
                                                                    <span class="tag">{{ __('Proxy') }}</span>
                                                                    <span class="tag">{{ __('Domain') }}</span>
                                                                    <span class="tag">{{ __('Servers') }}</span>
                                                                    <span class="tag">{{ __('Subscriptions') }}</span>
                                                                    <span class="tag">{{ __('Online Shopping') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <!-- Platinum Card -->
                                            <div class="card-option card-type-container platinum-card"
                                                data-card-type="platinum">
                                                <div class="row g-4">
                                                    <div class="col-xl-10 col-md-10">
                                                        <div class="card-info-content">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="card-radio me-3">
                                                                    <input type="radio" name="cardType" id="card-platinum"
                                                                        value="platinum">
                                                                    <span class="radio-custom"></span>
                                                                </div>
                                                                <div>
                                                                    <h4 class="mb-2">{{ __('Platinum Card') }}</h4>
                                                                    <p class="text-muted mb-3">
                                                                        {{ __('Designed for growing teams, fintech platforms, or global enterprises that need full access and automation.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-10 col-md-10">
                                                        <div class="card-preview-wrapper">
                                                            <div class="card-preview">
                                                                <div class="card-content"
                                                                    style="background-image: url({{ get_image($card_api->platinum_image ?? '', 'card-api') }})">
                                                                    <!-- Logo in top left corner -->
                                                                    <div class="card-logo">
                                                                        <img src="{{ get_fav($basic_settings) }}"
                                                                            alt="Logo">
                                                                    </div>
                                                                    <div class="card-number" id="previewCardNumber">**** ---
                                                                        ****</div>
                                                                    <div class="card-holder" id="previewCardHolder">
                                                                        {{ __('Card Holder Name') }}</div>
                                                                    <div class="card-balance balance-preview"
                                                                        id="previewBalance">--</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-10 col-md-10 col-12">
                                                        <div class="card-details">
                                                            <button class="details-toggle" type="button">
                                                                {{ __('View Details') }}
                                                                <span class="toggle-arrow">↓</span>
                                                            </button>
                                                            <div class="details-content">
                                                                <div class="details-tags">
                                                                    <span class="tag">{{ __('Everyday') }}</span>
                                                                    <span class="tag">{{ __('Physical') }}</span>
                                                                    <span class="tag">{{ __('ATM') }}</span>
                                                                    <span class="tag">{{ __('3D Secure') }}</span>
                                                                    <span class="tag">{{ __('Apple Pay') }}</span>
                                                                    <span class="tag">{{ __('Google Pay') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Step 2 Content -->
                        <div class="step-content" id="step2">
                            <div class="card-processor-step">
                                <div class="card-create-process">
                                    <div class="row">
                                        <!-- left Column - Card Creation Form -->
                                        <div class="col-xl-8  col-lg-6 col-md-6">
                                            <div class="card-creation-form">
                                                <form id="createCardForm">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <!-- Card Name -->
                                                            <div class="form-group">
                                                                <label>{{ __("Card Holder's Name") }}</label>
                                                                <input type="text" name="card_holder_name"
                                                                    class="form-control form--control"
                                                                    placeholder="{{ __('Enter card holder name') }}"
                                                                    id="cardName">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Card Type -->
                                                    <div class="form-group px-3">
                                                        <label>{{ __('Card Type') }}</label>
                                                        <div class="radio-wrapper">
                                                            <div class="radio-item provider-option selected"
                                                                data-provider="visa">
                                                                <input type="radio" name="provider" id="visa"
                                                                    value="visa" checked>
                                                                <label for="visa">
                                                                    <img src="{{ get_image('frontend/images/card/visa-svg.svg') }}"
                                                                        alt="VISA">
                                                                </label>
                                                            </div>
                                                            <div class="radio-item provider-option"
                                                                data-provider="mastercard">
                                                                <input type="radio" name="provider" id="mastercard"
                                                                    value="mastercard">
                                                                <label for="mastercard">
                                                                    <img src="{{ get_image('frontend/images/card/mastercard-svg.svg') }}"
                                                                        alt="Mastercard">
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <!-- Select Wallet -->
                                                            <label for="selectWallet"
                                                                class="form-label">{{ __('Select Wallet') }}</label>
                                                            <select class="form-select nice-select" name="wallet_currency"
                                                                id="selectWallet" required>
                                                                <option value="" disabled>{{ __('Choose Wallet') }}
                                                                </option>
                                                                @forelse ($wallets as $wallet)
                                                                    <option value="{{ $wallet->id }}"
                                                                        data-rate="{{ $wallet->currency->rate }}"
                                                                        data-currency-code="{{ $wallet->currency->code }}"
                                                                        data-currency-symbol="{{ $wallet->currency->symbol }}">
                                                                        {{ $wallet->currency->code }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <!-- Card Currency -->
                                                            <label for="cardCurrency"
                                                                class="form-label">{{ __('Card Currency') }}</label>
                                                            <select class="form-select nice-select" name="card_currency"
                                                                id="cardCurrency" required>
                                                                <option value="" disabled>
                                                                    {{ __('Select Currency') }}</option>
                                                                @forelse ($card_currencies as $currency)
                                                                    <option value="{{ $currency->id }}"
                                                                        data-currency-code="{{ $currency->currency_code }}"
                                                                        data-rate="{{ $currency->rate }}"
                                                                        data-min-limit="{{ $currency->min_limit }}"
                                                                        data-max-limit="{{ $currency->max_limit }}"
                                                                        data-daily-limit="{{ $currency->daily_limit }}"
                                                                        data-monthly-limit="{{ $currency->monthly_limit }}"
                                                                        data-universal-package-fee="{{ $currency->fees->cardyfie_universal_card_issues_fee }}"
                                                                        data-platinum-package-fee="{{ $currency->fees->cardyfie_platinum_card_issues_fee }}"
                                                                        data-card-deposit-fixed-fee="{{ $currency->fees->cardyfie_card_deposit_fixed_fee }}"
                                                                        data-card-withdraw-fixed-fee="{{ $currency->fees->cardyfie_card_withdraw_fixed_fee }}"
                                                                        data-card-maintenance-fixed-fee="{{ $currency->fees->cardyfie_card_maintenance_fixed_fee }}"
                                                                        data-currency-symbol="{{ $currency->currency_symbol }}">
                                                                        {{ $currency->currency_code }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- right Column - Card Preview & Fees -->
                                        <div class="col-xl-4 col-lg-6 col-md-6">
                                            <div class="card-preview-wrapper">
                                                <div class="card-preview">
                                                    <div class="card-content card-content-provider"
                                                        style="background-image: url({{ asset('backend/images/card-settings/universal.png') }})">
                                                        <!-- Logo in top left corner -->
                                                        <div class="card-logo">
                                                            <img src="{{ get_fav($basic_settings) }}" alt="Logo">
                                                        </div>
                                                        <div class="card-number" id="previewCardNumber">**** --- ****
                                                        </div>
                                                        <div class="card-holder" id="previewCardHolder">
                                                            {{ __('Card Holder Name') }}</div>
                                                        <div class="card-balance balance-preview" id="previewBalance">--
                                                        </div>
                                                        <div class="card-type">
                                                            <img src="{{ get_image('frontend/images/card/visa-svg.svg') }}"
                                                                id="previewBrandLogo" alt="VISA">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Fees Section below Card Preview -->
                                                <div class="fee-summary-section">
                                                    <div class="fee-summary">
                                                        <h6 class="fee-summary-title">{{ __('Fee Summary') }}</h6>
                                                        <div class="fee-item d-flex justify-content-between">
                                                            <span class="fee-label">{{ __('Exchange Rate') }}</span>
                                                            <span class="fee-value exchange-rate">--</span>
                                                        </div>
                                                        <div class="fee-item d-flex justify-content-between">
                                                            <span class="fee-label">{{ __('Card Issue Fee') }}</span>
                                                            <span class="fee-value issue-fee">--</span>
                                                        </div>
                                                        <div class="fee-divider"></div>
                                                        <div class="fee-total d-flex justify-content-between">
                                                            <span
                                                                class="fee-label">{{ __('Total Payable Amount') }}</span>
                                                            <span class="fee-value total-amount">--</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Step 3 Content -->
                        <div class="step-content" id="step3">
                            <div class="card-processor-step">
                                <div class="row justify-content-center">
                                    <!-- Left Column - Card Preview & Fees -->
                                    <div class="col-xl-5 col-lg-6 col-md-6">
                                        <div class="card-preview-wrapper">
                                            <div class="card-preview">
                                                <div class="card-background">
                                                    <!-- Background image will be set via CSS -->
                                                </div>
                                                <div class="card-content">
                                                    <!-- Logo in top left corner -->
                                                    <div class="card-logo">
                                                        <img src="{{ get_fav($basic_settings) }}" alt="Logo">
                                                    </div>
                                                    <div class="card-number">**** --- ****</div>
                                                    <div class="card-holder">Mostafijur Rahman</div>
                                                    <div class="card-balance">$0.00</div>
                                                    <div class="card-type">
                                                        <img src="{{ get_image('frontend/images/card/visa-svg.svg') }}"
                                                            alt="VISA">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="action-buttons ">
                                            <a href="{{ setRoute('user.cardyfie.virtual.card.create') }}" class="w-100">
                                                <button class="btn btn--base w-100">{{ __('Issue More Card') }}</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Process Footer -->
                <div class="process-footer">
                    <button class="btn btn-outline" id="backBtn" disabled>{{ __('Back') }}</button>
                    <button class="btn btn--base" id="nextBtn">{{ __('Next') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // Card selection functionality
        document.querySelectorAll('.card-type-container').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.card-type-container').forEach(c => {
                    c.classList.remove('selected');
                });

                // Add selected class to clicked card
                this.classList.add('selected');

                // Get selected card type
                const cardType = this.getAttribute('data-card-type');
                if (cardType == 'universal') {
                    const universalBg = "{{ get_image($card_api->universal_image ?? '', 'card-api') }}";
                    document.querySelectorAll(
                        '#step2 .card-content-provider, #step3 .card-content-provider, .card-preview .card-content-provider'
                    ).forEach(el => {
                        el.style.backgroundImage = `url('${universalBg}')`;
                    });
                } else {
                    const universalBg = "{{ get_image($card_api->platinum_image ?? '', 'card-api') }}";
                    document.querySelectorAll(
                        '#step2 .card-content-provider, #step3 .card-content-provider, .card-preview .card-content-provider'
                    ).forEach(el => {
                        el.style.backgroundImage = `url('${universalBg}')`;
                    });
                }

            });
        });
    </script>
    <script>
        // Provider selection
        document.querySelectorAll('.provider-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.provider-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

                // Add selected class to clicked option
                this.classList.add('selected');

                // Update preview card brand logo
                const provider = this.getAttribute('data-provider');
                const previewLogo = document.getElementById('previewBrandLogo');

                if (provider === 'visa') {
                    previewLogo.src = "{{ asset('frontend/images/card/visa-svg.svg') }}";
                    previewLogo.alt = 'Visa';
                } else if (provider === 'mastercard') {
                    previewLogo.src = "{{ asset('frontend/images/card/mastercard-svg.svg') }}";
                    previewLogo.alt = 'Mastercard';
                }
            });
        });

        // Update card preview when card name changes
        document.getElementById('cardName').addEventListener('input', function() {
            $('.card-holder').text(this.value || 'Card Holder Name');
        });

        // Button event handlers
        document.querySelector('.btn-back').addEventListener('click', function() {
            console.log('Back button clicked');
        });
    </script>
    <script>
        // Simple Process Navigation System
        let currentStep = 1;
        const totalSteps = 3;

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            showStep(currentStep);
            updateProcessIndicator();
        });

        // Show specific step and hide others
        function showStep(stepNumber) {
            // Hide all sections
            for (let i = 1; i <= totalSteps; i++) {
                const section = document.querySelector(`.step-${i}`);
                if (section) {
                    section.style.display = 'none';
                }
            }

            // Show current step
            const currentSection = document.querySelector(`.step-${stepNumber}`);
            if (currentSection) {
                currentSection.style.display = 'block';
            }

            currentStep = stepNumber;
            updateProcessIndicator();
        }

        // Update process indicator steps
        function updateProcessIndicator() {
            const indicators = document.querySelectorAll('.process-step');

            indicators.forEach((indicator, index) => {
                const stepNumber = index + 1;
                indicator.classList.remove('active', 'completed');

                if (stepNumber < currentStep) {
                    indicator.classList.add('completed');
                } else if (stepNumber === currentStep) {
                    indicator.classList.add('active');
                    // If we're on step 3, make it completed (green background)
                    if (stepNumber === 3) {
                        indicator.classList.add('completed');
                        indicator.classList.remove('active');
                    }
                }
            });

            // Update step titles
            const stepTitles = document.querySelectorAll('.step-title');
            stepTitles.forEach((title, index) => {
                const stepNumber = index + 1;
                title.classList.remove('active');

                if (stepNumber === currentStep) {
                    title.classList.add('active');
                }
            });
        }

        // Next button click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-next')) {
                e.preventDefault();
                if (currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            }
        });

        // Back button click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-back')) {
                e.preventDefault();
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            }
        });

        // Process step click (optional - click on step numbers to navigate)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('process-step') || e.target.parentElement.classList.contains(
                    'process-step')) {
                const stepElement = e.target.classList.contains('process-step') ? e.target : e.target.parentElement;
                const stepNumber = parseInt(stepElement.getAttribute('data-step'));
                if (stepNumber && stepNumber >= 1 && stepNumber <= totalSteps) {
                    showStep(stepNumber);
                }
            }
        });
    </script>

    <script>
        //_________________________________________
        // card create process || step 1
        //_________________________________________
        document.addEventListener('DOMContentLoaded', function() {
            // Card selection
            const cardOptions = document.querySelectorAll('.card-option');
            const radioInputs = document.querySelectorAll('input[name="provider"]');

            function selectCard(selectedCard) {
                console.log('Selected card type:', selectedCard.dataset.cardType);
                cardOptions.forEach(card => card.classList.remove('selected'));
                selectedCard.classList.add('selected');
                document.querySelector(`#card-${selectedCard.dataset.cardType}`).checked = true;
            }

            cardOptions.forEach(card => {
                card.addEventListener('click', (e) => {
                    if (!e.target.closest('.details-toggle')) {
                        selectCard(card);
                    }
                });
            });

            // Details toggle
            document.querySelectorAll('.details-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const detailsContent = this.nextElementSibling;
                    this.classList.toggle('active');
                    detailsContent.classList.toggle('show');
                });
            });
        });

        //_________________________________________
        // card create process || step 2
        //_________________________________________
        // card preview js
        document.addEventListener('DOMContentLoaded', function() {
            const createCardForm = document.getElementById('createCardForm');
            const cardPreview = document.querySelector('.card-content');

            // Form elements
            const cardNameInput = document.getElementById('cardName');
            const cardAmountInput = document.getElementById('cardAmount');
            const cardTypeRadios = document.querySelectorAll('input[name="provider"]');

            // Update card preview in real-time
            if (cardNameInput && cardPreview) {
                cardNameInput.addEventListener('input', function() {
                    const cardNameElement = cardPreview.querySelector('.card-holder');
                    if (cardNameElement) {
                        cardNameElement.textContent = this.value || 'Mostafijur Rahman';
                    }
                });
            }

            if (cardAmountInput && cardPreview) {
                cardAmountInput.addEventListener('input', function() {
                    const cardBalanceElement = cardPreview.querySelector('.card-balance');
                    if (cardBalanceElement) {
                        const amount = this.value ? `$${this.value}` : '$0.00';
                        cardBalanceElement.textContent = amount;
                    }
                });
            }

            // Update card type in preview with image
            cardTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const cardTypeElement = cardPreview.querySelector('.card-type');
                    if (cardTypeElement && this.checked) {
                        // Clear existing content
                        cardTypeElement.innerHTML = '';

                        // Create image element based on selected card type
                        const img = document.createElement('img');

                        if (this.value === 'visa') {
                            img.src = '{{ asset('frontend/images/card/visa-svg.svg') }}';
                            img.alt = 'VISA';
                        } else if (this.value === 'mastercard') {
                            img.src = '{{ asset('frontend/images/card/mastercard-svg.svg') }}';
                            img.alt = 'Mastercard';
                        }

                        // Add image to card type element
                        cardTypeElement.appendChild(img);
                    }
                });
            });
        });

        //_________________________________________
        // card create process || step 3
        //_________________________________________
        //** */ Minimal JavaScript for canvas toggle
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('cardCanvas');
            const openBtn = document.getElementById('openCanvasBtn');
            const closeBtn = document.getElementById('closeCanvasBtn');
            const backdrop = document.createElement('div');

            backdrop.className = 'canvas-backdrop';
            document.body.appendChild(backdrop);

            openBtn.addEventListener('click', function() {
                canvas.classList.add('active');
                backdrop.classList.add('active');
            });

            closeBtn.addEventListener('click', function() {
                canvas.classList.remove('active');
                backdrop.classList.remove('active');
            });

            backdrop.addEventListener('click', function() {
                canvas.classList.remove('active');
                backdrop.classList.remove('active');
            });
        });

        //_________________________________________
        // card create process layout beviour js
        //_________________________________________
        document.addEventListener('DOMContentLoaded', function() {
            const universalCardImage = "{{ get_image($card_api->universal_image ?? '', 'card-api') }}";
            const platinumCardImage = "{{ get_image($card_api->platinum_image ?? '', 'card-api') }}";

            const processHeader = document.getElementById('processHeader');
            const stepItems = document.querySelectorAll('.step-item');
            const stepContents = document.querySelectorAll('.step-content');
            const backBtn = document.getElementById('backBtn');
            const nextBtn = document.getElementById('nextBtn');

            let currentStep = 1;
            const totalSteps = stepItems.length;

            // Initialize the process
            updateProcess();

            // Header click to start process
            processHeader.addEventListener('click', function() {
                if (currentStep === 0) {
                    currentStep = 1;
                    updateProcess();
                }
            });

            // Step item click to navigate
            stepItems.forEach(item => {
                item.addEventListener('click', function() {
                    const step = parseInt(this.getAttribute('data-step'));
                    if (step <= currentStep) {
                        currentStep = step;
                        updateProcess();
                    }
                });
            });

            // Next button click
            nextBtn.addEventListener('click', function() {
                if (currentStep === 2) {
                    // On step 2, the 'Next' button becomes 'Create Card'
                    submitForm(); // Call the form submission function
                } else if (currentStep < totalSteps) {
                    currentStep++;
                    updateProcess();
                }
            });

            // Back button click
            backBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateProcess();
                }
            });

            function updateProcess() {
                // Update step items
                stepItems.forEach(item => {
                    const step = parseInt(item.getAttribute('data-step'));

                    item.classList.remove('active', 'completed');

                    if (step === currentStep) {
                        item.classList.add('active');
                    } else if (step < currentStep) {
                        item.classList.add('completed');
                    }
                });

                // Update step contents
                stepContents.forEach(content => {
                    content.classList.remove('active');
                });

                document.getElementById(`step${currentStep}`).classList.add('active');

                // Update buttons
                backBtn.disabled = currentStep === 1;

                if (currentStep === totalSteps) {
                    nextBtn.textContent = '{{ __('Finish') }}';
                } else if (currentStep === totalSteps - 1) {
                    nextBtn.textContent = '{{ __('Create Card') }}';
                } else {
                    nextBtn.textContent = '{{ __('Next') }}';
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            acceptVar();
            getExchangeRate();
            getFees();
            stepTwoValidation();
        });

        $('input[name="card_holder_name"]').on('input', function() {
            acceptVar();
            getFees();
            stepTwoValidation();
        });

        $("select[name=card_currency]").change(function() {
            acceptVar();
            getExchangeRate();
            getFees();
            stepTwoValidation();
        });
        $("select[name=wallet_currency]").change(function() {
            acceptVar();
            getExchangeRate();
            getFees();
            stepTwoValidation();
        });

        $(document).on('click', '.card-type-container', function() {
            acceptVar();
            getExchangeRate();
            getFees();
            stepOneValidation();
        });

        $(document).on('click', '.provider-option', function() {
            acceptVar();
            getExchangeRate();
            getFees();
            stepTwoValidation();
        });

        function acceptVar() {
            var cardHolderName = $("input[name=card_holder_name]").val();
            var cardCurrency = $("select[name=card_currency] :selected");
            var cardCurrencyId = cardCurrency.val();
            var cardCurrencyCode = cardCurrency.data('currency-code');
            var cardCurrencyRate = cardCurrency.data('rate');
            var cardCurrencyMinLimit = cardCurrency.data('min-limit');
            var cardCurrencyMaxLimit = cardCurrency.data('max-limit');
            var cardCurrencyDailyLimit = cardCurrency.data('daily-limit');
            var cardCurrencyMonthlyLimit = cardCurrency.data('monthly-limit');
            var cardCurrencyUniversalPackageFee = cardCurrency.data('universal-package-fee');
            var cardCurrencyPlatinumPackageFee = cardCurrency.data('platinum-package-fee');
            var cardCurrencyDepositFixedFee = cardCurrency.data('card-deposit-fixed-fee');
            var cardCurrencyWithdrawFixedFee = cardCurrency.data('card-withdraw-fixed-fee');
            var cardCurrencyMaintenanceFixedFee = cardCurrency.data('card-maintenance-fixed-fee');
            var cardCurrencySymbol = cardCurrency.data('currency-symbol');

            var wallet = $("select[name=wallet_currency] :selected");
            var walletId = wallet.val();
            var walletRate = wallet.data('rate');
            var walletCurrencyCode = wallet.data('currency-code');
            var walletCurrencySymbol = wallet.data('currency-symbol');
            var cardType = $('.card-type-container.selected').data('card-type');
            var cardProvider = document.querySelector('input[name="provider"]:checked')?.value;;

            return {
                cardHolderName: cardHolderName,
                cardCurrency: cardCurrency,
                cardCurrencyId: cardCurrencyId,
                cardCurrencyCode: cardCurrencyCode,
                cardCurrencyRate: cardCurrencyRate,
                cardCurrencyMinLimit: cardCurrencyMinLimit,
                cardCurrencyMaxLimit: cardCurrencyMaxLimit,
                cardCurrencyDailyLimit: cardCurrencyDailyLimit,
                cardCurrencyMonthlyLimit: cardCurrencyMonthlyLimit,
                cardCurrencyUniversalPackageFee: cardCurrencyUniversalPackageFee,
                cardCurrencyPlatinumPackageFee: cardCurrencyPlatinumPackageFee,
                cardCurrencyDepositFixedFee: cardCurrencyDepositFixedFee,
                cardCurrencyWithdrawFixedFee: cardCurrencyWithdrawFixedFee,
                cardCurrencyMaintenanceFixedFee: cardCurrencyMaintenanceFixedFee,
                cardCurrencySymbol: cardCurrencySymbol,
                cardProvider: cardProvider,

                wallet: wallet,
                walletId: walletId,
                walletRate: walletRate,
                walletCurrencyCode: walletCurrencyCode,
                walletCurrencySymbol: walletCurrencySymbol,

                cardType: cardType,

            };
        }

        function getExchangeRate() {
            var card_currency = acceptVar().cardCurrencyCode;
            var card_currency_rate = acceptVar().cardCurrencyRate;

            var wallet_currency = acceptVar().walletCurrencyCode;
            var wallet_currency_rate = acceptVar().walletRate;
            var rate = parseFloat(wallet_currency_rate) / parseFloat(card_currency_rate);

            if (wallet_currency == null || wallet_currency == "" || card_currency == null || card_currency == "") {
                return false;
            }

            $('.exchange-rate').html("1 " + card_currency + " = " + parseFloat(rate).toFixed(3) + " " + wallet_currency);

            return rate;
        }


        function feesCalculation() {
            var exchange_rate = getExchangeRate();
            if (acceptVar().cardType == 'universal') {
                var card_issue_charge = acceptVar().cardCurrencyUniversalPackageFee;
            } else if (acceptVar().cardType == 'platinum') {
                var card_issue_charge = acceptVar().cardCurrencyPlatinumPackageFee;
            }

            if ($.isNumeric(card_issue_charge)) {
                // Process Calculation
                var card_issue_calc = parseFloat(card_issue_charge) * parseFloat(exchange_rate);
                var total_charge = parseFloat(card_issue_calc);
                total_charge = parseFloat(total_charge).toFixed(8);
                var total_payable_amount = parseFloat(total_charge);
                // return total_charge;
                return {
                    issue: parseFloat(card_issue_calc).toFixed(8),
                    total: parseFloat(total_charge).toFixed(8),
                    total_payable: parseFloat(total_payable_amount).toFixed(8),
                };
            } else {
                // return "--";
                return false;
            }
        }

        function getFees() {
            var wallet_currency = acceptVar().walletCurrencyCode;
            var card_currency = acceptVar().cardCurrencySymbol;
            var charges = feesCalculation();

            if (charges == false || wallet_currency == null || wallet_currency == "" || card_currency == null ||
                card_currency == "") {
                return false;
            }

            $(".fees-show").html(parseFloat(charges.total).toFixed(3) + " " +
                wallet_currency);
            $(".issue-fee").html(parseFloat(charges.issue).toFixed(3) + " " +
                wallet_currency);
            $(".total-amount").html(parseFloat(charges.total_payable).toFixed(3) + " " +
                wallet_currency);
        }

        function stepOneValidation() {
            let cardType = acceptVar().cardType;
            if (cardType) {
                $('.step-two').removeClass('disabled');
            }
        }

        function stepTwoValidation() {
            let isValid = true;
            let cardHolderName = acceptVar().cardHolderName;
            let provider = acceptVar().cardProvider;
            let wallet = acceptVar().walletId;
            let cardCurrency = acceptVar().cardCurrencyId;

            if (!cardHolderName || cardHolderName == null || cardHolderName == "") {
                isValid = false;
            }

            if (!provider) {
                isValid = false;
            }

            if (!wallet) {
                isValid = false;
            }

            if (!cardCurrency) {
                isValid = false;
            }

            if (isValid) {
                $('.issue-card').removeClass('disabled');
            } else {
                $('.issue-card').addClass('disabled');
            }
        }

        function updateCardBackgrounds(cardType) {
            let imageUrl = (cardType === 'platinum') ? platinumCardImage : universalCardImage;
            $('#step2 .card-content-provider').css('background-image', `url(${imageUrl})`);
            $('#step3 .card-content-provider').css('background-image', `url(${imageUrl})`);
        }
        $('.card-option').on('click', function(e) {
            if ($(e.target).closest('.details-toggle').length) return;
            $('.card-option').removeClass('selected');
            $(this).addClass('selected');
            $(`#card-${$(this).data('type')}`).prop('checked', true);
            updateCardBackgrounds($(this).data('type'));
            updateAll();
        });
        const universalCardImageFinal = "{{ get_image($card_api->universal_image ?? '', 'card-api') }}";
        const platinumCardImageFinal = "{{ get_image($card_api->platinum_image ?? '', 'card-api') }}";

        function updateFinalCardView(card) {
            const finalCardPreview = document.querySelector('#step3 .card-preview-wrapper .card-content');

            if (finalCardPreview && card) {
                const holderElement = finalCardPreview.querySelector('.card-holder');
                const numberElement = finalCardPreview.querySelector('.card-number');
                const balanceElement = finalCardPreview.querySelector('.card-balance');
                const cardTypeImgElement = finalCardPreview.querySelector('.card-type img');

                holderElement.textContent = card.card_name || "Card Holder's Name";
                numberElement.textContent = card.masked_pan || "****************";


                const balance = parseFloat(card.amount || 0).toFixed(3);
                const currency = card.currency || '$';
                balanceElement.textContent = `${currency} ${balance}`;

                if (cardTypeImgElement && card.card_type) {
                    const visaImgSrc = "{{ asset('frontend/images/card/visa-svg.svg') }}";
                    const mastercardImgSrc = "{{ asset('frontend/images/card/mastercard-svg.svg') }}";

                    if (card.card_type.toLowerCase() === 'visa') {
                        cardTypeImgElement.src = visaImgSrc;
                        cardTypeImgElement.alt = 'VISA';
                    } else if (card.card_type.toLowerCase() === 'mastercard') {
                        cardTypeImgElement.src = mastercardImgSrc;
                        cardTypeImgElement.alt = 'Mastercard';
                    }
                }
                let imageUrl = (card.card_tier === 'platinum') ? platinumCardImageFinal : universalCardImageFinal;
                $('.card-background').css('background-image', `url(${imageUrl})`);
            }
        }

        function submitForm() {
            // Collect and validate input data
            const nextBtn = document.getElementById('nextBtn');
            let cardHolderName = acceptVar().cardHolderName.trim();
            let provider = acceptVar().cardProvider;
            let cardType = acceptVar().cardType;
            let cardCurrency = acceptVar().cardCurrencyCode;
            let wallet = acceptVar().walletCurrencyCode;

            // Prepare data for sending
            let data = {
                _token: $('meta[name="csrf-token"]').attr('content'), // Laravel CSRF token
                name_on_card: cardHolderName,
                card_tier: cardType,
                card_type: provider,
                currency: cardCurrency,
                from_currency: wallet
            };
            nextBtn.disabled = true;
            nextBtn.textContent = '{{ __('Processing...') }}';

            // Send AJAX POST request
            $.ajax({
                url: "{{ route('user.cardyfie.virtual.card.create') }}", // 🔹 your Laravel route name
                method: "POST",
                data: data,
                success: function(response) {
                    console.log(response);
                    throwMessage('success', [response.message.success.success[0]]);

                    const newCard = response.data?.card;
                    updateFinalCardView(newCard);
                    // 4. Advance to the final step
                    let currentStep = 3;
                    document.querySelectorAll('.step-item').forEach(item => {
                        const step = parseInt(item.getAttribute('data-step'));
                        item.classList.remove('active', 'completed');
                        if (step === currentStep) {
                            item.classList.add('completed', 'active');
                        } else if (step < currentStep) {
                            item.classList.add('completed');
                        }
                    });
                    document.querySelectorAll('.step-content').forEach(content => content.classList.remove(
                        'active'));
                    document.getElementById(`step${currentStep}`).classList.add('active');

                    // 5. Hide navigation buttons
                    nextBtn.style.display = 'none';
                    document.getElementById('backBtn').style.display = 'none';
                },
                error: function(xhr) {
                    nextBtn.disabled = false;
                    nextBtn.textContent = '{{ __('Create Card') }}';
                    console.log(xhr.responseJSON);
                    throwMessage('error', [xhr.responseJSON.message.error.error[0]]);
                },
                complete: function() {

                }
            });
        }
    </script>
@endpush
