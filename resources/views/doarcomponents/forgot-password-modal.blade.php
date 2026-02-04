{{-- Modal DOAR: Recuperar contraseña --}}
<div class="modal fade doar-forgot-modal" id="doarForgotPasswordModal" tabindex="-1" aria-labelledby="doarForgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content doar-forgot-modal__content">
            <div class="modal-header doar-forgot-modal__header border-0">
                <h5 class="modal-title " id="doarForgotPasswordModalLabel">
                    
                </h5>
                <button type="button" class="btn-close doar-forgot-modal__close" data-bs-dismiss="modal" aria-label="{{ __('Cerrar') }}"></button>
            </div>
            <div class="modal-body doar-forgot-modal__body">
                <h4 class="doar-forgot-modal__title">{{ __('Recuperar contraseña') }}</h4>
                <p class="doar-forgot-modal__text">
                    {{ __('Ingresa tu correo electrónico y te enviaremos un código de verificación.') }}
                </p>
                <form action="{{ setRoute('user.password.forgot.send.code') }}" method="POST" class="doar-forgot-modal__form">
                    @csrf
                    <div class="mb-3">
                        <label for="doarForgotEmail" class="form-label visually-hidden">{{ __('Correo electrónico') }}</label>
                        <div class="doar-forgot-modal__input-wrap">
                            <i class="fas fa-envelope doar-forgot-modal__input-icon"></i>
                            <input type="text"
                                class="form-control doar-forgot-modal__input"
                                id="doarForgotEmail"
                                name="credentials"
                                placeholder="{{ __('Correo electrónico') }}"
                                value="{{ old('credentials') }}"
                                required
                                autocomplete="email">
                        </div>
                        @error('credentials')
                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <x-security.google-recaptcha-field />
                    <button type="submit" class="btn doar-forgot-modal__btn w-100">
                        {{ __('Enviar código de verificación') }}
                    </button>
                </form>
                <div class="doar-forgot-modal__back text-center mt-4">
                    <a href="javascript:void(0)" class="doar-forgot-modal__back-link" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Volver al inicio de sesión') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
