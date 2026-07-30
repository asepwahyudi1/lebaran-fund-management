<?php

namespace App\Livewire\Customer;

use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadPayment extends Component
{
    use WithFileUploads;

    public $amount = '';
    public $payment_date = '';
    public $payment_method = 'Transfer Bank';
    public $proof;

    public function mount()
    {
        $this->payment_date = Carbon::today()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'proof' => 'required|image|max:2048', // 2MB Max
        ], [
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'proof.required' => 'Bukti transfer wajib diunggah.',
            'proof.image' => 'Bukti transfer harus berupa file gambar (JPG/PNG).',
            'proof.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        // Store file in public/receipts disk (linked to storage)
        $path = $this->proof->store('receipts', 'public');

        Payment::create([
            'user_id' => auth()->id(),
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'proof_path' => $path,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Bukti transfer berhasil diunggah. Menunggu verifikasi dari Admin.');

        $this->reset(['amount', 'proof']);
        $this->payment_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.customer.upload-payment')->layout('layouts.app');
    }
}
