<?php

namespace App\Livewire\Customer;

use App\Models\Payment;
use Livewire\Component;

class MyPackage extends Component
{
    public function render()
    {
        $customer = auth()->user()->load('packages');
        $customer->checkDueNotifications();
        
        $packages = $customer->packages;
        
        if ($packages->isEmpty()) {
            return view('livewire.customer.my-package', [
                'packages' => collect([]),
            ])->layout('layouts.app');
        }

        $calendar = $customer->getInstallmentCalendar();
        
        $totalWeeks = count($calendar);
        $verifiedWeeks = 0;
        $pendingWeeks = 0;
        $lateWeeks = 0;
        $unpaidWeeks = 0;

        foreach ($calendar as $week) {
            if ($week['status'] === 'verified') {
                $verifiedWeeks++;
            } elseif ($week['status'] === 'pending') {
                $pendingWeeks++;
            } elseif ($week['status'] === 'late') {
                $lateWeeks++;
            } else {
                $unpaidWeeks++;
            }
        }

        $progressPercent = $totalWeeks > 0 ? min(100, round(($verifiedWeeks / $totalWeeks) * 100)) : 0;
        
        $totalPaid = Payment::where('user_id', $customer->id)
            ->where('status', 'verified')
            ->sum('amount');

        return view('livewire.customer.my-package', [
            'customer' => $customer,
            'packages' => $packages,
            'calendar' => $calendar,
            'totalWeeks' => $totalWeeks,
            'verifiedWeeks' => $verifiedWeeks,
            'pendingWeeks' => $pendingWeeks,
            'lateWeeks' => $lateWeeks,
            'unpaidWeeks' => $unpaidWeeks,
            'progressPercent' => $progressPercent,
            'totalPaid' => $totalPaid,
        ])->layout('layouts.app');
    }
}
