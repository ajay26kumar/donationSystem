<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/donate', [DonationController::class, 'showForm'])->name('donation.form');
Route::post('/donate', [DonationController::class, 'submit'])->name('donation.submit');

Route::post('/donation/final', [DonationController::class, 'checkout'])->name('donation.final');
// Route::post('/donation/process', [DonationController::class, 'processPayment'])->name('donation.process');

Route::post('/donation/checkout', [DonationController::class, 'createCheckoutSession'])->name('donation.checkout');
Route::get('/donation/success', [DonationController::class, 'verifyPayment'])->name('donation.success');

Route::get('/donation/cancel', function () {
    return view('cancel');
})->name('donation.cancel');
