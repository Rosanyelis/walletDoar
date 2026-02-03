<div class="col-xl-12 col-lg-12 mb-20">
    <div class="custom-card mt-10">
        <div class="dashboard-header-wrapper">
            <h4 class="title">{{ __("Create Customer") }} </h4>
        </div>
         <form action="{{ setRoute('user.cardyfie.virtual.card.create.customer') }}" class="card-form" method="POST"
                enctype="multipart/form-data">
                @csrf
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>{{ __('First Name') }}<span>*</span></label>
                    <input type="text" class="form--control" name="first_name"
                                placeholder="{{ __('Enter First Name') }}..."
                                value="{{ old('first_name', $user->firstname) }}">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>{{ __('Last Name') }}<span>*</span></label>
                      <input type="text" class="form--control" name="last_name"
                                placeholder="{{ __('Enter Last Name') }}..."
                                value="{{ old('last_name', $user->lastname) }}">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>{{ __('Email Name') }}<span>*</span></label>
                    <input type="email" class="form--control" name="email"
                                value="{{ old('customer_email', $user->email) }}"
                                placeholder="{{ __('Enter Email') }}...">
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>{{ __('Date Of Birth') }}<span>*</span> <small>(
                                {{ __('Should match with tour ID') }} )</small></label>
                    <input type="date" class="form--control" name="date_of_birth"
                                value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('Identity Type') }} <span class="text--base">*</span></label>
                    <select class="form--control" name="identity_type" required>
                        <option disabled selected value="null">{{ __('Choose One') }}</option>
                        <option value="nid">{{ __('National ID Card (NID)') }}</option>
                        <option value="passport">{{ __('Passport') }}</option>
                        <option value="bvn">{{ __('Bank Verification Number') }}</option>
                    </select>
                </div>
                    <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('Identity Number') }} <span class="text--base">*</span></label>
                    <input type="text" class="form--control" required
                                placeholder="{{ __('Enter Identity Number') }}" name="identity_number"
                                value="{{ old('identity_number') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('ID Card Image (Font Side)') }} <span class="text--base">*</span></label>
                    <div class="file-holder-wrapper">
                        <input type="file" class="form--control" required name="id_front_image">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('ID Card Image (Back Side)') }} <span class="text--base">*</span></label>
                    <div class="file-holder-wrapper">
                        <input type="file" class="form--control" required name="id_back_image">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('Your Photo') }} <span class="text--base">*</span></label>
                    <div class="file-holder-wrapper">
                        <input type="file" class="form--control" required name="user_image">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('House Number') }} <span class="text--base">*</span></label>
                    <input type="text" class="form--control" required name="house_number"
                                placeholder="{{ __('Enter House Number') }}" value="{{ old('house_number') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('Country') }} <span class="text--base">*</span></label>
                    <select class="form--control select2-basic" name="country" required>
                        <option disabled value="null">{{ __('Select Country') }}</option>
                        @foreach (get_all_countries() ?? [] as $country)
                            <option value="{{ $country->iso2 }}"
                                {{ $user->address->country == $country->name ? 'selected' : '' }}>
                                {{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('City') }} <span class="text--base">*</span></label>
                    <input type="text" class="form--control" required name="city"
                            placeholder="{{ __('Enter City') }}"
                            value="{{ old('city', $user->address->city ?? '') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('State') }} <span class="text--base">*</span></label>
                    <input type="text" class="form--control" required name="state"
                            placeholder="{{ __('Enter State') }}"
                            value="{{ old('state', $user->address->state ?? '') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('Zip Code') }} <span class="text--base">*</span></label>
                    <input type="text" class="form--control" required name="zip_code"
                            placeholder="{{ __('Enter Zip Code') }}"
                            value="{{ old('zip_code', $user->address->zip_code ?? '') }}">
                </div>
                <div class="col-xl-6 col-lg-6 form-group mb-3">
                    <label>{{ __('Address') }} <span class="text--base">*</span></label>
                    <textarea class="form--control" name="address" id="address" cols="30" rows="10" required>{{ old('address', $user->address->address ?? '') }}</textarea>
                </div>
                <div class="col-xl-12 col-lg-12 pt-5">
                    <button type="submit" class="btn--base w-100">{{ __("Submit") }} </button>
                </div>
            </div>
        </form>
    </div>
</div>
