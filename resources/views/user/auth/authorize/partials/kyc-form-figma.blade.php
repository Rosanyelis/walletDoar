@php
    $kyc_arr = is_array($kyc_fields) ? $kyc_fields : (array) $kyc_fields;
    $collection = collect($kyc_arr);
    $selfie_field = $collection->first(function ($f) {
        $name = strtolower($f->name ?? '');
        $label = strtolower($f->label ?? '');
        return ($f->type ?? '') === 'file' && (str_contains($name, 'selfie') || str_contains($label, 'selfie'));
    });
    if (!$selfie_field) {
        $first_file = $collection->where('type', 'file')->first();
        if ($first_file && $collection->where('type', 'file')->count() === 1) {
            $selfie_field = $first_file;
        }
    }
    $business_fields = $collection->filter(function ($f) {
        $name = strtolower($f->name ?? '');
        $label = strtolower($f->label ?? '');
        return str_contains($name, 'negocio') || str_contains($name, 'business') || str_contains($label, 'negocio') || str_contains($label, 'business');
    })->values()->all();
    $business_names = collect($business_fields)->pluck('name')->filter()->all();
    $personal_fields = $collection->reject(function ($f) use ($selfie_field, $business_names) {
        if ($selfie_field && isset($f->name) && $f->name === $selfie_field->name) {
            return true;
        }
        return in_array($f->name ?? null, $business_names, true);
    })->values()->all();
@endphp
<form action="{{ setRoute('user.authorize.kyc.submit') }}" method="POST" enctype="multipart/form-data" class="kyc-form-figma">
    @csrf
    <div class="row g-4">
        {{-- Columna izquierda: Datos Personales --}}
        <div class="col-lg-6">
            <h4 class="kyc-section-title">{{ __("Datos Personales") }}</h4>
            <p class="kyc-section-desc">{{ __("Completa la siguiente información para verificar tu cuenta. Todos los datos serán revisados manualmente.") }}</p>

            @foreach ($personal_fields as $item)
                @if ($item->type == 'text')
                    <div class="kyc-form-group">
                        <label for="kyc-{{ $item->name }}">{{ $item->label }}</label>
                        <input type="text" name="{{ $item->name }}" id="kyc-{{ $item->name }}" class="kyc-form-control" placeholder="{{ $item->label }}" value="{{ old($item->name) }}" @if(!empty($item->required)) required @endif>
                        @error($item->name)
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        @if (stripos($item->label ?? '', 'irección') !== false || stripos($item->name ?? '', 'address') !== false || stripos($item->name ?? '', 'domicilio') !== false)
                            <div class="kyc-alert-warning">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#F59E0B" d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm1 14H9v-2h2v2zm0-4H9V5h2v5z"/></svg>
                                <span>{{ __("Coloque una dirección válida que coincida con su perfil") }}</span>
                            </div>
                        @endif
                    </div>
                @elseif ($item->type == 'textarea')
                    <div class="kyc-form-group">
                        <label for="kyc-{{ $item->name }}">{{ $item->label }}</label>
                        <textarea name="{{ $item->name }}" id="kyc-{{ $item->name }}" class="kyc-form-control" rows="3" placeholder="{{ $item->label }}" @if(!empty($item->required)) required @endif>{{ old($item->name) }}</textarea>
                        @error($item->name)
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                @elseif ($item->type == 'select')
                    <div class="kyc-form-group">
                        <label for="kyc-{{ $item->name }}">{{ $item->label }}</label>
                        <div class="kyc-select-wrap">
                            <select name="{{ $item->name }}" id="kyc-{{ $item->name }}" class="kyc-form-control" @if(!empty($item->required)) required @endif>
                                <option value="" disabled selected>{{ __("Seleccionar") }}</option>
                                @foreach ($item->validation->options ?? [] as $opt)
                                    <option value="{{ $opt }}" @if(old($item->name) == $opt) selected @endif>{{ $opt }}</option>
                                @endforeach
                            </select>
                            <svg class="kyc-select-arrow" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        @error($item->name)
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                @elseif ($item->type == 'file')
                    <div class="kyc-form-group">
                        <label>{{ $item->label }}</label>
                        <label class="kyc-upload-zone position-relative" for="file-{{ $item->name }}">
                            <input type="file" name="{{ $item->name }}" id="file-{{ $item->name }}" accept="{{ is_array($item->validation->mimes ?? null) && count($item->validation->mimes) ? implode(',', $item->validation->mimes) : 'image/*' }}">
                            <span class="kyc-upload-icon">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 6v20M6 16h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </span>
                            <span class="kyc-upload-text">{{ __("Seleccionar archivo") }}</span>
                        </label>
                        <p class="kyc-upload-hint">{{ $item->label === 'Comprobante de domicilio' ? __("Recibo público o privado que valida la dirección") : __("Foto de documento de identificación") }}</p>
                        @error($item->name)
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                @endif
            @endforeach

            @if (count($personal_fields) === 0 && count($kyc_arr) > 0)
                @include('user.components.generate-kyc-fields', ['fields' => $kyc_fields])
            @endif
        </div>

        {{-- Columna derecha: Selfie + Datos del negocio --}}
        <div class="col-lg-6">
            {{-- Selfie --}}
            @if ($selfie_field && $selfie_field->type == 'file')
                <h4 class="kyc-section-title">{{ __("Selfie") }}</h4>
                <p class="kyc-section-desc">{{ __("Verifica tu identidad con un autorretrato claro. Asegúrese de que tu rostro se vea claramente visible en el encuadre.") }}</p>
                <div class="kyc-form-group">
                    <label class="kyc-selfie-box position-relative d-block" for="file-selfie-{{ $selfie_field->name }}">
                        <input type="file" name="{{ $selfie_field->name }}" id="file-selfie-{{ $selfie_field->name }}" accept="{{ implode(',', $selfie_field->validation->mimes ?? ['image/*']) }}">
                        <span class="kyc-selfie-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="24" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 52c0-11 8.954-20 20-20s20 9 20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </span>
                        <span class="kyc-btn-selfie">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h2l1.5-2.5h3L10 7h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
                            {{ __("Tomar selfie") }}
                        </span>
                    </label>
                    @error($selfie_field->name)
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            @elseif ($selfie_field)
                <h4 class="kyc-section-title">{{ __("Selfie") }}</h4>
                <p class="kyc-section-desc">{{ __("Verifica tu identidad con un autorretrato claro. Asegúrese de que tu rostro se vea claramente visible en el encuadre.") }}</p>
                <div class="kyc-form-group">
                    <label class="kyc-selfie-box position-relative d-block" for="file-selfie-main">
                        <input type="file" name="{{ $selfie_field->name }}" id="file-selfie-main" accept="{{ implode(',', $selfie_field->validation->mimes ?? ['image/*']) }}">
                        <span class="kyc-selfie-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="24" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 52c0-11 8.954-20 20-20s20 9 20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </span>
                        <span class="kyc-btn-selfie">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h2l1.5-2.5h3L10 7h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
                            {{ __("Tomar selfie") }}
                        </span>
                    </label>
                    @error($selfie_field->name)
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            @else
                <h4 class="kyc-section-title">{{ __("Selfie") }}</h4>
                <p class="kyc-section-desc">{{ __("Verifica tu identidad con un autorretrato claro. Asegúrese de que tu rostro se vea claramente visible en el encuadre.") }}</p>
                <div class="kyc-selfie-box">
                    <span class="kyc-selfie-icon">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="24" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 52c0-11 8.954-20 20-20s20 9 20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </span>
                    <span class="kyc-btn-selfie">{{ __("Tomar selfie") }}</span>
                </div>
            @endif

            {{-- Datos del negocio --}}
            <h4 class="kyc-section-title mt-4">{{ __("Datos del negocio") }}</h4>
            @if (count($business_fields) > 0)
                @foreach ($business_fields as $item)
                    @if ($item->type == 'text')
                        <div class="kyc-form-group">
                            <label for="kyc-b-{{ $item->name }}">{{ $item->label }}</label>
                            <input type="text" name="{{ $item->name }}" id="kyc-b-{{ $item->name }}" class="kyc-form-control" placeholder="{{ __("Número de identificación") }}" value="{{ old($item->name) }}" @if(!empty($item->required)) required @endif>
                            @error($item->name)
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @elseif ($item->type == 'file')
                        <div class="kyc-form-group">
                            <label>{{ $item->label }}</label>
                            <label class="kyc-upload-zone position-relative" for="file-b-{{ $item->name }}">
                                <input type="file" name="{{ $item->name }}" id="file-b-{{ $item->name }}" accept="{{ implode(',', $item->validation->mimes ?? ['image/*']) }}">
                                <span class="kyc-upload-icon">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 6v20M6 16h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </span>
                                <span class="kyc-upload-text">{{ __("Seleccionar archivo") }}</span>
                            </label>
                            <p class="kyc-upload-hint">{{ __("Foto de documento de identificación") }}</p>
                            @error($item->name)
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @endif
                @endforeach
            @else
                <div class="kyc-form-group">
                    <label>{{ __("Tipo de documento de identificación del negocio") }}</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="kyc-registro-tributario" name="registro_tributario" value="1">
                        <label class="form-check-label" for="kyc-registro-tributario">{{ __("Registro tributario") }}</label>
                    </div>
                </div>
                <div class="kyc-form-group">
                    <label for="kyc-business-id">{{ __("Identificación del negocio") }}</label>
                    <input type="text" name="business_id" id="kyc-business-id" class="kyc-form-control" placeholder="{{ __("Número de identificación") }}">
                </div>
                <div class="kyc-form-group">
                    <label>{{ __("Comprobante de identidad negocio") }}</label>
                    <label class="kyc-upload-zone position-relative" for="file-business-doc">
                        <input type="file" name="business_doc" id="file-business-doc" accept="image/*">
                        <span class="kyc-upload-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 6v20M6 16h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </span>
                        <span class="kyc-upload-text">{{ __("Seleccionar archivo") }}</span>
                    </label>
                    <p class="kyc-upload-hint">{{ __("Foto de documento de identificación") }}</p>
                </div>
                <div class="kyc-form-group">
                    <label>{{ __("Foto de oficinas o establecimiento del negocio (Foto clara)") }}</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <label class="kyc-btn-photo">
                            <input type="file" name="office_photo_interior" accept="image/*" class="d-none">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h2l1.5-2.5h3L10 7h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
                            {{ __("Foto interior") }}
                        </label>
                        <label class="kyc-btn-photo">
                            <input type="file" name="office_photo_exterior" accept="image/*" class="d-none">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7h2l1.5-2.5h3L10 7h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="11" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
                            {{ __("Foto exterior") }}
                        </label>
                    </div>
                </div>
            @endif

            <div class="kyc-form-group mt-3">
                <button type="submit" class="kyc-btn-verify">{{ __("Verificar") }}</button>
            </div>
        </div>
    </div>
</form>
