<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/properties', [PublicController::class, 'properties'])->name('properties');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/property/{property}', [PublicController::class, 'showProperty'])->name('property.show');
Route::get('/service/{service}', [PublicController::class, 'showService'])->name('service.show');
Route::post('/lead/{type}/{id}', [PublicController::class, 'trackLead'])->name('lead.track');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/properties', [AdminController::class, 'properties'])->name('properties');
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::get('/leads', [AdminController::class, 'leads'])->name('leads');
    Route::get('/invoices', [AdminController::class, 'invoices'])->name('invoices');
    Route::post('/invoices/generate', [AdminController::class, 'generateInvoices'])->name('invoices.generate');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/site-settings', [AdminController::class, 'siteSettings'])->name('site-settings');
    Route::put('/site-settings', [AdminController::class, 'updateSiteSettings'])->name('site-settings.update');
});

Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');
    Route::resource('properties', DeveloperController::class);
    Route::get('/analytics', [DeveloperController::class, 'analytics'])->name('analytics');
    Route::get('/invoices', [DeveloperController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}/view', [DeveloperController::class, 'viewInvoice'])->name('invoices.view');
    Route::get('/invoices/{invoice}/download', [DeveloperController::class, 'downloadInvoice'])->name('invoices.download');
    Route::post('/invoices/{invoice}/pay', [DeveloperController::class, 'payInvoice'])->name('invoices.pay');
    Route::get('/payment/return', [DeveloperController::class, 'paymentReturn'])->name('payment.return');
});

Route::middleware(['auth', 'role:service_provider'])->prefix('service-provider')->name('service-provider.')->group(function () {
    Route::get('/dashboard', [ServiceProviderController::class, 'dashboard'])->name('dashboard');
    Route::resource('services', ServiceProviderController::class);
    Route::get('/analytics', [ServiceProviderController::class, 'analytics'])->name('analytics');
    Route::get('/invoices', [ServiceProviderController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}/view', [ServiceProviderController::class, 'viewInvoice'])->name('invoices.view');
    Route::get('/invoices/{invoice}/download', [ServiceProviderController::class, 'downloadInvoice'])->name('invoices.download');
    Route::post('/invoices/{invoice}/pay', [ServiceProviderController::class, 'payInvoice'])->name('invoices.pay');
    Route::get('/payment/return', [ServiceProviderController::class, 'paymentReturn'])->name('payment.return');
});

require __DIR__.'/auth.php';