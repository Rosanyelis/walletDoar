@extends('user.layouts.layoutweb')
@php
    $lang = selectedLang();
    $auth_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::AUTH_SECTION);
    $auth_text = App\Models\Admin\SiteSections::getData($auth_slug)->first();
    $login_title = @$auth_text->value->language->$lang->login_title ?: __('Bienvenido de nuevo');
    $login_subtitle = @$auth_text->value->language->$lang->login_text ?: __('Ingresa a tu cuenta');
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/doar/auth.css') }}">
@endpush

@section('content')
    <section class="login-doar-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                {{-- Columna izquierda: promocional --}}
                <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                    <div class="login-promo-wrap">
                        <div class="login-promo">
                            <div class="login-logo text--base">
                                <img src="{{ get_logo($basic_settings) }}" alt="logo">
                            </div>
                            <h1 class="login-slogan ">{{ __('Tu plata al instante') }}</h1>
                            <ul class="login-benefits">
                                <li>
                                    <i class="fas fa-bolt"></i>
                                    <span>{{ __('Envía y recibe dinero en') }} <strong>{{ __('minutos') }}</strong>.</span>
                                </li>
                                <li>
                                    <i class="fas fa-credit-card"></i>
                                    <span>{{ __('Retira directo a tu') }} <strong>{{ __('cuenta bancaria') }}</strong>.</span>
                                </li>
                                <li>
                                    <i class="fas fa-shield-alt"></i>
                                    <span>{{ __('Seguridad financiera de') }} <strong>{{ __('última tecnología') }}</strong>.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Columna derecha: formulario --}}
                <div class="col-lg-5 col-md-8">
                    <div class="login-card">
                        <form action="{{ setRoute('user.login.submit') }}" method="POST">
                            @csrf
                            <h2 class="form-title text-center">{{ $login_title }}</h2>
                            <p class="form-subtitle text-center">{{ $login_subtitle }}</p>
                            @php
                                $email_demo = (env('APP_MODE') == 'demo') ? 'user@appdevs.net' : '';
                                $pass_demo = (env('APP_MODE') == 'demo') ? 'appdevs' : '';
                            @endphp

                            <div class="row">
                                <div class="col-12 form-group">
                                    <label class="input-label">{{ __('Correo electrónico') }}</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="text"
                                            class="form-control form--control @error('credentials') is-invalid @enderror"
                                            name="credentials"
                                            value="{{ old('credentials', $email_demo) }}"
                                            placeholder="{{ __('Correo electrónico') }}"
                                            required
                                            autocomplete="username">
                                    </div>
                                    @error('credentials')
                                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 form-group show_hide_password">
                                    <label class="input-label">{{ __('Contraseña') }}</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password"
                                            class="form-control form--control"
                                            name="password"
                                            value="{{ $pass_demo }}"
                                            placeholder="{{ __('Contraseña') }}"
                                            required
                                            autocomplete="current-password">
                                        <a href="javascript:void(0)" class="show-pass" aria-label="{{ __('Mostrar contraseña') }}">
                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-12 form-group text-end">
                                    <a href="javascript:void(0)" class="forgot-link" data-bs-toggle="modal" data-bs-target="#doarForgotPasswordModal">{{ __('¿Olvidaste tu contraseña?') }}</a>
                                </div>

                                <x-security.google-recaptcha-field />

                                <div class="col-12 form-group">
                                    <button type="submit" class="btn btn-login-doar">{{ __('Iniciar sesión') }}</button>
                                </div>

                                <div class="col-12 text-center pt-2">
                                    <p class="text-muted-doar mb-1">{{ __('¿No tienes una cuenta?') }}</p>
                                    <a href="{{ setRoute('user.register') }}" class="link-doar">{{ __('Regístrate ahora') }}</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('doarcomponents.forgot-password-modal')
@endsection

@push('script')
@endpush
