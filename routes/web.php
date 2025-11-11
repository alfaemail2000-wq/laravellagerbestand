<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// --------------------------------
// 🧱 Livewire-Komponenten importieren
// --------------------------------
use App\Livewire\Items\Index as ItemsIndex;
use App\Livewire\Items\Create as ItemsCreate;
use App\Livewire\Items\Edit as ItemsEdit;
use App\Livewire\Items\Show as ItemsShow;

use App\Livewire\Movements\Inbound as MovementsInbound;
use App\Livewire\Movements\Transfer as MovementsTransfer;
use App\Livewire\Movements\Outbound as MovementsOutbound;
use App\Livewire\Movements\Production as MovementsProduction;

// --------------------------------
// 🏠 Startseite
// --------------------------------
Route::get('/', function () {
    return view('welcome');
})->name('home');

// --------------------------------
// 📊 Dashboard (Standard Breeze)
// --------------------------------
Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --------------------------------
// ⚙️ Authentifizierte Bereiche
// --------------------------------
Route::middleware(['auth'])->group(function () {

    // --------------------------------
    // 👤 Benutzer-Einstellungen (Volt)
    // --------------------------------
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // --------------------------------
    // 📦 Artikelverwaltung (ITEMS CRUD)
    // --------------------------------
    Route::get('/items', ItemsIndex::class)->name('items.index');
    Route::get('/items/create', ItemsCreate::class)->name('items.create');
    Route::get('/items/{item}', ItemsShow::class)->name('items.show');
    Route::get('/items/{item}/edit', ItemsEdit::class)->name('items.edit');

    // --------------------------------
    // 🚚 Warenbewegungen (MOVEMENTS)
    // --------------------------------
    Route::prefix('movements')->group(function () {
        // Wareneingänge (Lieferungen ins Hauptlager)
        Route::get('/inbound', MovementsInbound::class)->name('movements.inbound');

        // Transfers (zwischen Lagern)
        Route::get('/transfer', MovementsTransfer::class)->name('movements.transfer');

        // Warenausgänge (Verbrauch / Versand)
        Route::get('/outbound', MovementsOutbound::class)->name('movements.outbound');

        // Produktion (Materialverbrauch + Fertigprodukt buchen)
        Route::get('/production', MovementsProduction::class)->name('movements.production');
    });
});

// --------------------------------
// 🔐 Authentifizierung (Breeze)
// --------------------------------
require __DIR__.'/auth.php';
