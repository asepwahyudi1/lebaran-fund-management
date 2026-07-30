<?php

namespace App\Livewire\Customer;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentHistory extends Component
{
    use WithPagination;

    // Lightbox / Detail Modal properties
    public $isOpenDetail = false;
    public $detailPayment = null;

    public function openDetailModal($id)
    {
        $this->detailPayment = Payment::where('user_id', auth()->id())->findOrFail($id);
        $this->isOpenDetail = true;
    }

    public function closeDetailModal()
    {
        $this->isOpenDetail = false;
        $this->detailPayment = null;
    }

    public function render()
    {
        $payments = Payment::where('user_id', auth()->id())
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('livewire.customer.payment-history', [
            'payments' => $payments,
        ])->layout('layouts.app');
    }
}
