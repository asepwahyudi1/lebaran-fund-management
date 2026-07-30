<?php

namespace App\Livewire\Customer;

use App\Models\Payment;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $customer = auth()->user()->load('package');
        
        $totalPaid = Payment::where('user_id', $customer->id)
            ->where('status', 'verified')
            ->sum('amount');

        $packagePrice = $customer->package->price ?? 0;
        $remainingBalance = max(0, $packagePrice - $totalPaid);
        
        $progressPercent = $packagePrice > 0 ? min(100, round(($totalPaid / $packagePrice) * 100)) : 0;

        // Recent Payments
        $recentPayments = Payment::where('user_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('livewire.customer.dashboard', [
            'customer' => $customer,
            'totalPaid' => $totalPaid,
            'remainingBalance' => $remainingBalance,
            'progressPercent' => $progressPercent,
            'recentPayments' => $recentPayments,
        ])->layout('layouts.app');
    }
}
