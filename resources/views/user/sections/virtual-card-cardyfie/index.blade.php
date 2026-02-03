@extends('user.layouts.master')

@push('css')
    <style>
        a[disabled] {
            pointer-events: none;
            /* prevents clicking */
            color: gray;
            /* makes it look inactive */
            cursor: not-allowed !important;
            text-decoration: none;
            opacity: 0.6;
        }

        button[disabled] {
            pointer-events: none;
            /* prevents clicking */
            color: gray;
            /* makes it look inactive */
            cursor: not-allowed !important;
            text-decoration: none;
            opacity: 0.6;
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
        'active' => __('Virtual Card'),
    ])
@endsection

@section('content')
    <div class="row justify-content-center mt-30">
        @if ($card_customer == null)
            @include('user.sections.virtual-card-cardyfie.component.create-customer')
        @endif
        @if (isset($card_customer))
            @if (
                (isset($card_customer->status) && $card_customer->status == global_const()::CARD_CUSTOMER_PENDING_STATUS) ||
                    $card_customer->status == global_const()::CARD_CUSTOMER_REJECTED_STATUS)
                @include('user.sections.virtual-card-cardyfie.component.check-customer-status')
            @endif
        @endif

        @if (isset($card_customer))
            {{-- For Live Mode --}}
            @if (isset($card_customer->status) && $card_customer->status == global_const()::CARD_CUSTOMER_APPROVED_STATUS)
                @include('user.sections.virtual-card-cardyfie.component.create-card')
            @endif
        @endif
    </div>
@endsection
@push('script')
    <script>
        // Card data
        const cardData = {
            fullNumber: '4288 3645 7890 0763',
            maskedNumber: '4288 36****** 0763',
            cvv: '543',
            maskedCVV: '***',
            expiry: '09/2027',
            billingAddress: '2381 Zanker Rd Ste 110, San Jose, CA, 95131, US'
        };

        let isCardVisible = false;

        // Modal functions
        function openModal() {
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal on overlay click
        document.getElementById('modalOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Toggle card visibility
        function toggleCardVisibility() {
            const cardNumberEl = document.getElementById('cardNumber');
            const cvvNumberEl = document.getElementById('cvvNumber');
            const eyeIcon = document.getElementById('eyeIcon');

            if (isCardVisible) {
                // Hide details
                cardNumberEl.textContent = cardData.maskedNumber;
                cvvNumberEl.textContent = cardData.maskedCVV;
                // Use Font Awesome class names instead of emoji
                eyeIcon.className = 'fa-solid fa-eye';
                isCardVisible = false;
            } else {
                // Show details
                cardNumberEl.textContent = cardData.cardNumber;
                cvvNumberEl.textContent = cardData.cvvNumber;
                // Use Font Awesome class names instead of emoji
                eyeIcon.className = 'fa-solid fa-eye-slash';
                isCardVisible = true;
            }
        }

        // Copy functions
        function copyToClipboard(text, message = 'Copied to clipboard!') {
            navigator.clipboard.writeText(text).then(() => {
                showCopyNotification(message);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showCopyNotification(message);
            });
        }

        function showCopyNotification(message) {
            const notification = document.getElementById('copyNotification');
            notification.textContent = message;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 2000);
        }

        function copyCardNumber() {
            const text = document.querySelector("#cardNumber").innerText.trim();
            console.log(text);
            copyToClipboard(text, 'Card number copied!');
        }

        function copyExpiry() {
            const text = document.querySelector("#expiryDate").innerText.trim();
            copyToClipboard(text, 'Expiry date copied!');
        }

        function copyCVV() {
            const text = document.querySelector("#cvvNumber").innerText.trim();
            copyToClipboard(text, 'CVV copied!');
        }

        function copyBillingAddress() {
            const text = document.querySelector("#billingAddress").innerText.trim();
            copyToClipboard(text, 'Billing address copied!');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>


    <script>
        const universalCardImageFinal = "{{ get_image($card_api->universal_image ?? '', 'card-api') }}";
        const platinumCardImageFinal = "{{ get_image($card_api->platinum_image ?? '', 'card-api') }}";
        $('.card-details').on('click', function() {
            cardId = $(this).data('card-id');
            $('.card-loading').removeClass('d-none');
            $('.card-show').addClass('d-none');
            $('.freeze-workable').addClass('d-none');
            $('.freeze-loading').removeClass('d-none');
            $('#billingAddress p').html('loading...');
            $('#cardBalance').html('loading...');
            $('#deposit-loading').html(`<a class="btn--base w-100">{{ __('Loading') }}...</a>`);
            $('.withdraw-loading').html(`<a class="withdraw-btn">{{ __('Loading') }}...</a>`);
            $('#transaction-loading').html(`<a class="view-transactions-btn">{{ __('Loading') }}...</a>`);
            $('#close-loading').html(`<a class="close-card-btn">{{ __('Loading') }}...</a>`);

            $.ajax({
                url: "{{ route('user.cardyfie.virtual.card.details') }}",
                method: 'GET',
                data: {
                    card_id: cardId // <-- your dynamic ID variable
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success:', response.data);

                    cardDetails = response.data.card_details;
                    myCard = response.data.my_card;


                    depositUrl = `{{ url('user/cardyfie-virtual-card/deposit/page') }}/${myCard.id}`;
                    withdrawUrl = `{{ url('user/cardyfie-virtual-card/withdraw/page') }}/${myCard.id}`;
                    transactionUrl =
                    `{{ url('user/cardyfie-virtual-card/transaction/') }}/${myCard.id}`;

                    $('#cardNumber').text(cardDetails.real_pan ?? 'N/A');
                    $('#holderName').text(cardDetails.card_name ?? 'N/A');
                    $('#expiryDate').text(cardDetails.card_exp_time ?? 'N/A');
                    $('#cvvNumber').text(cardDetails.cvv ?? '***');
                    $('#cardBalance').text(parseFloat(myCard.amount).toFixed(2) + " " + cardDetails
                        .card_currency_code);
                    $('#billingAddress').html('<p>' + (cardDetails.address ?? 'No address available') +
                        '</p>');
                    $('#freezeBtn').attr('data-card-id', myCard.id);
                    $('#freezeBtn').attr('data-card-status', myCard.status);
                    let cardType = (cardDetails.card_type === 'platinum') ? "Platinum" : "Universal";
                    $('#cardTypeOnModal').text(cardType ?? 'N/A');

                    if (myCard.status == 'CLOSED') {
                        $('#deposit-loading').html(
                            `<a class="btn--base w-100 text-white" disabled href="${depositUrl}">{{ __('Deposit') }} ✓ </a>`
                            )
                        $('.withdraw-loading').html(
                            `<a class="withdraw-btn" disabled href="${withdrawUrl}">{{ __('Withdraw') }} ↗ </a>`
                            )
                        $('#transaction-loading').html(
                            `<a class="view-transactions-btn" disabled href="${transactionUrl}">{{ __('View Transactions') }} → </a>`
                            )
                        $('#close-loading').html(
                            `<a class="close-card-btn btn--base w-100 text-white" disabled data-card-id="${myCard.id}">{{ __('Close Card') . ' ' }} <i class="fas fa-times"></i> </a>`
                            )
                        $('#freezeBtn').attr('disabled', true);
                    } else {
                        $('#deposit-loading').html(
                            `<a class="btn--base w-100" href="${depositUrl}">{{ __('Deposit') }} ✓ </a>`
                            )
                        $('.withdraw-loading').html(
                            `<a class="withdraw-btn" href="${withdrawUrl}">{{ __('Withdraw') }} ↗ </a>`
                            )
                        $('#transaction-loading').html(
                            `<a class="view-transactions-btn" href="${transactionUrl}">{{ __('View Transactions') }} → </a>`
                            )
                        $('#close-loading').html(
                            `<a class="close-card-btn btn--base w-100" data-card-id="${myCard.id}">{{ __('Close Card') . ' ' }} <i class="fas fa-times"></i> </a>`
                            )
                        if (myCard.status == 'FREEZE') {
                            $('#freezeBtn').html('{{ __('Unfreeze') }} <i class="fas fa-eraser"></i>');
                        } else {
                            $('#freezeBtn').attr('disabled', false);
                            $('#freezeBtn').html('{{ __('Freeze') }} <i class="fas fa-eraser"></i>');
                        }
                    }

                    if (myCard.status == 'ENABLED') {
                        $('.card-status .status-text').html('Active');
                        $('.card-status .status-dot').removeClass('bg-warning');
                        $('.card-status .status-dot').removeClass('bg-danger');
                        $('.card-status .status-dot').addClass('bg-success');
                    }
                    if (myCard.status == 'CLOSED') {
                        $('.card-status .status-text').html('Closed');
                        $('.card-status .status-dot').removeClass('bg-warning');
                        $('.card-status .status-dot').removeClass('bg-success');
                        $('.card-status .status-dot').addClass('bg-danger');
                    }
                    if (myCard.status == 'FREEZE') {
                        $('.card-status .status-text').html('Freezed');
                        $('.card-status .status-dot').removeClass('bg-success');
                        $('.card-status .status-dot').removeClass('bg-danger');
                        $('.card-status .status-dot').addClass('bg-warning');
                    }

                    if (cardDetails.card_provider == "visa") {
                        $('.card-brand-container').html(
                            `<img class="card-brand" src="{{ asset('frontend/images/card/visa-svg.svg') }}" alt="VISA logo">`
                            );
                    } else {
                        $('.card-brand-container').html(
                            `<img class="card-brand" src="{{ asset('frontend/images/card/mastercard-svg.svg') }}" alt="MASTER logo">`
                            );
                    }

                    let imageUrl = (cardDetails.card_type === 'platinum') ? platinumCardImageFinal :
                        universalCardImageFinal;
                    $('.card-section').css('background-image', `url(${imageUrl})`);


                    $('.card-loading').addClass('d-none');
                    $('.card-show').removeClass('d-none');
                    $('.freeze-loading').addClass('d-none');
                    $('.freeze-workable').removeClass('d-none');

                },
                error: function(xhr, status, error) {
                    throwMessage('error', [xhr.responseJSON.message.error.error[0]]);
                }

            });
        });


        $(document).on('click', '.close-card-btn', function() {
            let button = $(this);
            let target = button.data('card-id');

            $.ajax({
                url: "{{ route('user.cardyfie.virtual.card.close') }}", // Laravel route
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // always include CSRF
                    target: target
                },
                beforeSend: function() {
                    button.prop('disabled', true).text('Processing...');
                },
                success: function(response) {
                    closeModal();
                    throwMessage('success', [response.message.success.success[0]]);

                },
                error: function(xhr) {
                    throwMessage('error', [xhr.responseJSON.message.error.error[0]]);
                },
            });
        });



        $(document).on('click', '#freezeBtn', function() {
            let button = $(this);
            let status = button.data('card-status');
            let target = button.data('card-id');
            let newStatus;

            $.ajax({
                url: "{{ route('user.cardyfie.virtual.card.change.status') }}", // Laravel route
                method: "PUT",
                data: {
                    _token: "{{ csrf_token() }}", // always include CSRF
                    status: status,
                    data_target: target
                },
                beforeSend: function() {
                    button.prop('disabled', true).text('Processing...');
                },
                success: function(response) {
                    if (status == 'ENABLED') {
                        button.prop('disabled', false).data('card-status', 'FREEZE').html(
                            '{{ __('Unfreeze') }} <i class="fas fa-eraser"></i>');
                        $('.card-status .status-text').html('Freezed');
                        $('.card-status .status-dot').removeClass('bg-success');
                        $('.card-status .status-dot').addClass('bg-warning');

                    } else {
                        button.prop('disabled', false).data('card-status', 'ENABLED').html(
                            '{{ __('Freeze') }} <i class="fas fa-eraser"></i>');
                        $('.card-status .status-text').html('Active');
                        $('.card-status .status-dot').removeClass('bg-warning');
                        $('.card-status .status-dot').addClass('bg-success');

                    }
                    throwMessage('success', [response.message.success.success[0]]);

                },
                error: function(xhr) {
                    console.log(xhr.responseJSON.message);
                    throwMessage('error', [xhr.responseJSON.message.error.error[0]]);
                },
            });
        });
    </script>
@endpush
