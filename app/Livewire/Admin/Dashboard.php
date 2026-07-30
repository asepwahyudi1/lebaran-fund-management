<?php

namespace App\Livewire\Admin;

use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalPackages = Package::count();
        $totalPayments = Payment::count();
        $totalIncome = Payment::where('status', 'verified')->sum('amount');
        
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();
        $todayPaymentsCount = Payment::whereDate('payment_date', Carbon::today())->count();
        
        $newCustomersCount = User::where('role', 'customer')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Recent Payments
        $recentPayments = Payment::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent Customers
        $recentCustomers = User::where('role', 'customer')
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalCustomers' => $totalCustomers,
            'totalPackages' => $totalPackages,
            'totalPayments' => $totalPayments,
            'totalIncome' => $totalIncome,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'todayPaymentsCount' => $todayPaymentsCount,
            'newCustomersCount' => $newCustomersCount,
            'recentPayments' => $recentPayments,
            'recentCustomers' => $recentCustomers,
        ])->layout('layouts.app');
    }
}
