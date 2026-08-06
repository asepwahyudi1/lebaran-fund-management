<?php

namespace App\Livewire\Admin;

use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;

class Reports extends Component
{
    public function render()
    {
        // 1. General Stats
        $totalCustomers = User::where('role', 'customer')->count();
        $totalVerifiedIncome = Payment::where('status', 'verified')->sum('amount');
        
        // 2. Report by Package
        $packagesReport = Package::withCount(['users as customers_count' => function ($q) {
            $q->where('role', 'customer');
        }])->get()->map(function ($package) {
            // Get all verified payments for customers in this package
            $verifiedAmount = Payment::where('status', 'verified')
                ->whereHas('user.packages', function ($q) use ($package) {
                    $q->where('packages.id', $package->id);
                })->sum('amount');

            // Total potential collection = (price * customer count)
            $potentialAmount = $package->price * $package->customers_count;
            $remainingAmount = max(0, $potentialAmount - $verifiedAmount);

            return [
                'name' => $package->name,
                'price' => $package->price,
                'customers_count' => $package->customers_count,
                'potential_amount' => $potentialAmount,
                'verified_amount' => $verifiedAmount,
                'remaining_amount' => $remainingAmount,
                'progress_percent' => $potentialAmount > 0 ? round(($verifiedAmount / $potentialAmount) * 100) : 0,
            ];
        });

        // 3. Transactions recap list
        $recentTransactions = Payment::with('user.packages')
            ->where('status', 'verified')
            ->orderBy('payment_date', 'desc')
            ->take(20)
            ->get();

        return view('livewire.admin.reports', [
            'totalCustomers' => $totalCustomers,
            'totalVerifiedIncome' => $totalVerifiedIncome,
            'packagesReport' => $packagesReport,
            'recentTransactions' => $recentTransactions,
        ])->layout('layouts.app');
    }
}
