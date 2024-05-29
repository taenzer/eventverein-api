<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventProductLinkController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\EventOwnerOrShareOnly;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class)->middleware(EventOwnerOrShareOnly::class);
    Route::put("events/{event}/status", [EventController::class, 'toggleStatus'])->name("events.status.toggle")->middleware(EventOwnerOrShareOnly::class);
    Route::post("events/{event}/report", [EventController::class, 'generateReport'])->name("events.report.create")->middleware(EventOwnerOrShareOnly::class);
    Route::resource('products', ProductController::class);

    Route::get('events/{event}/products/add', [EventProductLinkController::class, 'create'])->name("events.products.add")->middleware(EventOwnerOrShareOnly::class);
    Route::post('events/{event}/products/add', [EventProductLinkController::class, 'store'])->name("events.products.store")->middleware(EventOwnerOrShareOnly::class);
    Route::patch('events/{event}/products/', [EventProductLinkController::class, 'update'])->name("events.products.update")->middleware(EventOwnerOrShareOnly::class);
    Route::delete('events/{event}/products/', [EventProductLinkController::class, 'destroy'])->name("events.products.destroy")->middleware(EventOwnerOrShareOnly::class);

    Route::get('events/{event}/shares', [ShareController::class, 'index'])->name("events.shares.index")->middleware(EventOwnerOrShareOnly::class);
    Route::post('events/{event}/shares/store', [ShareController::class, 'store'])->name("events.shares.store")->middleware(EventOwnerOrShareOnly::class);
    Route::delete('events/{event}/shares/{share}/destroy', [ShareController::class, 'destroy'])->name("events.shares.destroy")->middleware(EventOwnerOrShareOnly::class);

    Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create');

});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/devices', [ProfileController::class, 'devices'])->name('profile.devices');
    Route::delete('/profile/devices', [ProfileController::class, 'removeDevice'])->name('profile.devices.destroy');
});

require __DIR__ . '/auth.php';