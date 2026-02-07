<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\SiteController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sitio público (página web)
|--------------------------------------------------------------------------
|
| URLs del sitio público (para referencia y futuras modificaciones):
|
|   /                    → Entrada: redirige al login público (user.login).
|   /developer           → Página developer (name: developer).
|   /about-us            → Nosotros (name: aboutUs).
|   /services            → Servicios (name: services).
|   /web-journal         → Blog / Web Journal (name: webJournal).
|   /web-journal/details/{id}/{slug} → Detalle de entrada (name: webJournal.details).
|   /web-journal/category/{id}      → Entradas por categoría (name: webJournal.by.category).
|   /contact-us          → Contacto (name: contactUs).
|   /page/{slug}         → Página dinámica por slug (name: page.view).
|   /faq                 → Preguntas frecuentes (name: faq).
|   /success             → Callback éxito pago (name: walletiumPaymentSuccess).
|   /cancel              → Callback cancelación pago (name: walletiumPaymentCancel).
|
| Login público del sitio (usuarios): /login → ver routes/auth.php (user.login).
|
*/
// Entrada por defecto: redirección directa al login público del sitio (usuarios).
Route::get('/', function () {
    return redirect()->route('user.login');
})->name('index');

// --- Página pública como entrada (comentado para futuras modificaciones) ---
// Para que (/) muestre el home del sitio en lugar del login, descomentar la línea siguiente y comentar/eliminar la redirección de arriba.
// Route::get('/', [SiteController::class, 'home'])->name('index');

Route::controller(SiteController::class)->group(function(){
    // URLs del sitio público (ver comentario al inicio del archivo).
    Route::get('developer','developer')->name('developer');
    Route::get('api-docs', [App\Http\Controllers\ApiDocsController::class, 'index'])->name('api-docs');
    Route::get('about-us','aboutUs')->name('aboutUs');
    Route::get('services','services')->name('services');
    Route::get('web-journal','webJournal')->name('webJournal');

    Route::get('web-journal/details/{id}/{slug}','webJournalDetails')->name('webJournal.details');
    Route::get('web-journal/category/{id}','webJournalByCategory')->name('webJournal.by.category');

    Route::get('contact-us','contactUs')->name('contactUs');
    Route::post('contact/store','contactStore')->name('contact.store');

    Route::get('page/{slug}','pageView')->name('page.view');
    Route::get('faq','faq')->name('faq');

    Route::get('success','walletiumPaymentSuccess')->name('walletiumPaymentSuccess');
    Route::get('cancel','walletiumPaymentCancel')->name('walletiumPaymentCancel');
});