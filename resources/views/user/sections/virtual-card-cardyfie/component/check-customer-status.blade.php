<div class="col-xl-8">
    <div class="custom-card">
        <div class="card-body">
            <div class="dash-payment-item-wrapper">
                <div class="dash-payment-item active">
                    <div class="dash-payment-title-area">
                        <h5 class="title">{{ __('Status of the customer you created') }} : <span
                                class="text-warning">{{ Str::upper($card_customer->status) }}</span></h5>
                    </div>
                    <div class="dash-payment-body">
                        <p class="fw-bold">
                            <small>{{ __('Please wait until your customer status is APPROVED. Once it is APPROVED, you can continue with the card creation.') }}</small>
                        </p>
                        <div class="mt-20 d-flex justify-content-center align-items-center">
                            <a href="{{ setRoute('user.cardyfie.virtual.card.edit.customer') }}"
                                class="btn--base">{{ __('Update Customer') }}</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
