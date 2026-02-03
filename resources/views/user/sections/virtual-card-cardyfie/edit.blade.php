@extends('user.layouts.master')

@push('css')
    <style>
        .kyc-preview-wrapper{
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 20px;
        }
        .kyc-preview-wrapper li .thumb{
            width: 200px;
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.412);
            padding: 10px;

        }
        .kyc-preview-wrapper li img{
            object-fit: cover;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
        }
        .kyc-preview-wrapper li .label{
        margin-bottom: 8px;
        display: block;
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
        'active' => __('Update Card Customer'),
    ])
@endsection

@section('content')
<div class="row mt-30">
    <div class="col-xxl-10 col-xl-10 col-lg-10">
        <div class="custom-card">
            <div class="card-body">
                <div class="dashboard-header-wrapper">
                    <div class=" d-flex justify-content-start">
                        <a href="{{ setRoute('user.cardyfie.virtual.card.index') }}" class="btn--base"><i class="las la-arrow-left"></i></a>
                    </div>
                </div>
                <form action="{{ setRoute('user.cardyfie.virtual.card.update.customer') }}" class="card-form"
                    method="POST" enctype="multipart/form-data">
                    @method("PUT")
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('First Name') }}<span>*</span></label>
                            <div class="input-group max">
                                <input type="text" class="form--control" name="first_name"
                                    placeholder="{{ __('Enter First Name') }}..."
                                    value="{{ old('first_name', $card_customer->first_name) }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Last Name') }}<span>*</span></label>
                            <div class="input-group max">
                                <input type="text" class="form--control" name="last_name"
                                    placeholder="{{ __('Enter Last Name') }}..."
                                    value="{{ old('last_name', $card_customer->last_name) }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Date Of Birth') }}<span>*</span> <small>(
                                    {{ __('Should match with tour ID') }} )</small></label>
                            <div class="input-group max">
                                <input type="date" class="form--control" name="date_of_birth"
                                    value="{{ old('date_of_birth', $card_customer->date_of_birth) }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Identity Type') }} <span class="text--base">*</span></label>
                            <select class="form--control" name="identity_type" required>
                                <option disabled selected value="null">{{ __('Choose One') }}</option>
                                <option value="nid" {{ $card_customer->id_type == 'nid' ? 'selected' :'' }}>{{ __('National ID Card (NID)') }}</option>
                                <option value="passport" {{ $card_customer->id_type == 'passport' ? 'selected' :'' }}>{{ __('Passport') }}</option>
                                <option value="bvn" {{ $card_customer->id_type == 'bvn' ? 'selected' :'' }}>{{ __('Bank Verification Number') }}</option>
                            </select>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Identity Number') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form--control" required
                                    placeholder="{{ __('Enter Identity Number') }}" name="identity_number"
                                    value="{{ old("identity_number",$card_customer->id_number) }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('ID Card Image (Font Side)') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form--control" name="id_front_image">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('ID Card Image (Back Side)') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form--control" name="id_back_image">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Your Photo') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form--control" name="user_image">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('House Number') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form--control" required name="house_number"
                                    placeholder="{{ __('Enter House Number') }}"
                                    value="{{ old('house_number',$card_customer->house_number) }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Country') }} <span class="text--base">*</span></label>
                            <select class="form--control select2-basic" name="country" required>
                                <option disabled selected value="null">{{ __('Select Country') }}</option>
                                @foreach (get_all_countries() ?? [] as $country)
                                    <option value="{{ $country->iso2 }}"
                                        {{ $user->address->country ?? '' == $country->name ? 'selected' : '' }}>
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('City') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form--control" required name="city"
                                    placeholder="{{ __('Enter City') }}"
                                    value="{{ old('city',$card_customer->city ?? "") }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('State') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form--control" required name="state"
                                    placeholder="{{ __('Enter State') }}"
                                    value="{{ old('state', $card_customer->state ?? "") }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Zip Code') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form--control" required name="zip_code"
                                    placeholder="{{ __('Enter Zip Code') }}"
                                    value="{{ old('zip_code', $card_customer->zip_code ?? "") }}">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label>{{ __('Address') }} <span class="text--base">*</span></label>
                            <div class="input-group">
                                <textarea class="form--control" name="address" id="address" cols="30" rows="10" required>{{ old('address',$card_customer->address_line_1 ?? "") }}</textarea>
                            </div>
                        </div>
                    </div>
                    <ul class="kyc-preview-wrapper">
                                <li>
                                    <span class="label">{{ __("ID Card Image (Font Side)") }}:</span>
                                    <div class="thumb">
                                        <img src="{{ $card_customer->id_front_image ?? "" }}" alt="no-file">
                                    </div>
                                </li>
                                <li>
                                    <span class="label">{{__("ID Card Image (Back Side)") }}:</span>
                                    <div class="thumb">
                                        <img src="{{ $card_customer->id_back_image ?? "" }}" alt="no-file">
                                    </div>
                                </li>
                                <li>
                                    <span class="label">{{__("Your Photo") }}:</span>
                                    <div class="thumb">
                                        <img src="{{ $card_customer->user_image ?? "" }}" alt="no-file">
                                    </div>
                                </li>
                            </ul>
                    <div class="col-xl-12 col-lg-12">
                        <button type="submit" class="btn--base w-100"><span
                                class="w-100">{{ __('Continue') }}</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
