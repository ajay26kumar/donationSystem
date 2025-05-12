<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/donate', [DonationController::class, 'showForm'])->name('donation.form');
Route::post('/donate', [DonationController::class, 'submit'])->name('donation.submit');