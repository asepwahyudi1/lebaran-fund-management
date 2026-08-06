<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component
{
    use WithPagination;

    // Filters
    public $filterStatus = '';
    public $searchCustomer = '';

    // Manual Payment Form properties
    public $user_id = '';
    public $amount = '';
    public $payment_date = '';
    public $payment_method = 'Tunai';
    public $isOpenManual = false;

    // Verification Modal properties
    public $isOpenVerify = false;
    public $verifyingPaymentId = null;
    public $verifyingPayment = null;
    public $admin_notes = '';

    public function mount()
    {
        $this->payment_date = Carbon::today()->format('Y-m-d');
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingSearchCustomer()
    {
        $this->resetPage();
    }

    // Manual Payment Methods
    #[On('open-manual-modal-event')]
    public function openManualModal()
    {
        $this->resetManualFields();
        $this->isOpenManual = true;
    }

    public function closeManualModal()
    {
        $this->isOpenManual = false;
    }

    private function resetManualFields()
    {
        $this->user_id = '';
        $this->amount = '';
        $this->payment_date = Carbon::today()->format('Y-m-d');
        $this->payment_method = 'Tunai';
    }

    public function saveManual()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $payment = Payment::create([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'status' => 'verified', // manual admin inputs are verified by default!
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'admin_notes' => 'Diinput secara manual oleh Admin',
        ]);

        \App\Models\Notification::create([
            'user_id' => $payment->user_id,
            'title' => 'Pembayaran Dicatat Admin 💰',
            'message' => 'Pembayaran setoran sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah dicatat dan diverifikasi secara manual oleh Admin.',
            'type' => 'payment_verified',
            'is_read' => false,
        ]);

        session()->flash('message', 'Pembayaran manual berhasil dicatat.');
        $this->closeManualModal();
    }

    // Verification Methods
    public function openVerifyModal($id)
    {
        $this->verifyingPaymentId = $id;
        $this->verifyingPayment = Payment::with('user.package')->findOrFail($id);
        $this->admin_notes = $this->verifyingPayment->admin_notes ?? '';
        $this->isOpenVerify = true;
    }

    public function closeVerifyModal()
    {
        $this->isOpenVerify = false;
        $this->verifyingPaymentId = null;
        $this->verifyingPayment = null;
        $this->admin_notes = '';
    }

    public function verifyPayment()
    {
        $payment = Payment::findOrFail($this->verifyingPaymentId);
        $payment->status = 'verified';
        $payment->admin_notes = $this->admin_notes ?: 'Bukti transfer valid';
        $payment->verified_by = auth()->id();
        $payment->verified_at = now();
        $payment->save();

        \App\Models\Notification::create([
            'user_id' => $payment->user_id,
            'title' => 'Bukti Transfer Terverifikasi! ✅',
            'message' => 'Bukti transfer setoran sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah berhasil diverifikasi oleh Admin.',
            'type' => 'payment_verified',
            'is_read' => false,
        ]);

        session()->flash('message', 'Pembayaran berhasil diverifikasi.');
        $this->closeVerifyModal();
    }

    public function rejectPayment()
    {
        $this->validate([
            'admin_notes' => 'required|string|min:5',
        ], [
            'admin_notes.required' => 'Catatan alasan penolakan wajib diisi.',
            'admin_notes.min' => 'Catatan penolakan minimal 5 karakter.',
        ]);

        $payment = Payment::findOrFail($this->verifyingPaymentId);
        $payment->status = 'rejected';
        $payment->admin_notes = $this->admin_notes;
        $payment->verified_by = auth()->id();
        $payment->verified_at = now();
        $payment->save();

        \App\Models\Notification::create([
            'user_id' => $payment->user_id,
            'title' => 'Bukti Transfer Ditolak ❌',
            'message' => 'Bukti transfer setoran sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' ditolak oleh Admin. Alasan: "' . $payment->admin_notes . '". Silakan periksa kembali bukti transfer Anda.',
            'type' => 'payment_rejected',
            'is_read' => false,
        ]);

        session()->flash('message', 'Pembayaran ditolak.');
        $this->closeVerifyModal();
    }

    public function render()
    {
        $query = Payment::with('user.packages');

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->searchCustomer) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->searchCustomer . '%');
            });
        }

        return view('livewire.admin.payments', [
            'paymentsList' => $query->orderBy('created_at', 'desc')->paginate(15),
            'customers' => User::where('role', 'customer')->orderBy('name', 'asc')->get(),
        ])->layout('layouts.app');
    }
}
