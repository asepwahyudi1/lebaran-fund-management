<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Packages as AdminPackages;
use App\Livewire\Admin\Customers as AdminCustomers;
use App\Livewire\Admin\Payments as AdminPayments;
use App\Livewire\Admin\Reports as AdminReports;

use App\Livewire\Customer\Dashboard as CustomerDashboard;
use App\Livewire\Customer\UploadPayment as CustomerUploadPayment;
use App\Livewire\Customer\PaymentHistory as CustomerPaymentHistory;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/packages', AdminPackages::class)->name('packages');
    Route::get('/customers', AdminCustomers::class)->name('customers');
    Route::get('/payments', AdminPayments::class)->name('payments');
    Route::get('/reports', AdminReports::class)->name('reports');
});

// Customer Routes
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', CustomerDashboard::class)->name('dashboard');
    Route::get('/upload-payment', CustomerUploadPayment::class)->name('upload-payment');
    Route::get('/payment-history', CustomerPaymentHistory::class)->name('payment-history');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
