@php
    $is_approved = ($status ?? '') === 'approved';
@endphp
<div class="kyc-status-card kyc-status-card--{{ $is_approved ? 'approved' : 'pending' }}">
    <div class="kyc-status-icon-wrap">
        @if ($is_approved)
            {{-- Escudo con check (verde) --}}
            <svg class="kyc-status-icon" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M40 8L12 18v18c0 18 12 32 28 36 16-4 28-18 28-36V18L40 8z" fill="#E8F5E9" stroke="#4CAF50" stroke-width="2" stroke-linejoin="round"/>
                <path d="M32 40l6 6 14-14" stroke="#4CAF50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
        @else
            {{-- Escudo con reloj (amarillo/naranja) --}}
            <svg class="kyc-status-icon" width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M40 8L12 18v18c0 18 12 32 28 36 16-4 28-18 28-36V18L40 8z" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2" stroke-linejoin="round"/>
                <circle cx="40" cy="38" r="12" stroke="#F59E0B" stroke-width="2" fill="none"/>
                <path d="M40 32v6l4 4" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
            </svg>
        @endif
    </div>
    <h2 class="kyc-status-title">{{ __("Verificación KYC") }}</h2>
    <div class="kyc-status-badge-pill">
        @if ($is_approved)
            <span class="kyc-status-badge-dot kyc-status-badge-dot--approved">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 6l3 3 5-6" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <span class="kyc-status-badge-text">{{ __("Verificado") }}</span>
        @else
            <span class="kyc-status-badge-dot kyc-status-badge-dot--pending">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="6" cy="6" r="5" stroke="#F59E0B" stroke-width="1.5" fill="none"/><path d="M6 3v3l2 2" stroke="#F59E0B" stroke-width="1" stroke-linecap="round"/></svg>
            </span>
            <span class="kyc-status-badge-text">{{ __("Pendiente") }}</span>
        @endif
    </div>
    <div class="kyc-status-messages">
        @if ($is_approved)
            <p class="kyc-status-message">{{ __("Tu cuenta de negocio fue") }} <strong>{{ __("verificada") }}</strong> {{ __("correctamente") }}</p>
            <p class="kyc-status-message kyc-status-message--sub">{{ __("Ya puedes usar todas las funciones de DOAR sin restricciones.") }}</p>
        @else
            <p class="kyc-status-message">{{ __("Tu información KYC fue enviada y está en revisión.") }}</p>
            <p class="kyc-status-message kyc-status-message--sub">{{ __("Cuando tu verificación sea aprobada podrás usar todas las funciones sin restricciones.") }}</p>
        @endif
    </div>
    <div class="kyc-status-divider"></div>
</div>
