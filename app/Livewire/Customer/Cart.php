<?php

namespace App\Livewire\Customer;

use App\Models\Package;
use App\Models\Notification;
use Livewire\Component;
use Carbon\Carbon;

class Cart extends Component
{
    public $cartItems = [];
    public $totalWeeklyInstallment = 0;
    public $isOpenCheckoutModal = false;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $cart = session('cart', []);
        $this->cartItems = [];
        $this->totalWeeklyInstallment = 0;

        if (empty($cart)) {
            return;
        }

        $packages = Package::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $packageId => $qty) {
            if (isset($packages[$packageId])) {
                $pkg = $packages[$packageId];
                $weekly = (float) ($pkg->price / ($pkg->duration_weeks ?: 40));
                
                $this->cartItems[] = [
                    'package' => $pkg,
                    'quantity' => $qty,
                    'weekly_installment' => $weekly,
                    'subtotal' => $weekly * $qty,
                ];
                
                $this->totalWeeklyInstallment += $weekly * $qty;
            }
        }
    }

    public function updateQuantity($packageId, $change)
    {
        $cart = session('cart', []);
        if (isset($cart[$packageId])) {
            $cart[$packageId] += $change;
            if ($cart[$packageId] < 1) {
                $cart[$packageId] = 1;
            }
            session(['cart' => $cart]);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($packageId)
    {
        $cart = session('cart', []);
        if (isset($cart[$packageId])) {
            unset($cart[$packageId]);
            session(['cart' => $cart]);
            $this->loadCart();
            $this->dispatch('cart-updated');
            session()->flash('message', 'Paket berhasil dihapus dari keranjang.');
        }
    }

    public function checkout()
    {
        if (empty($this->cartItems)) {
            session()->flash('error', 'Keranjang Anda kosong.');
            return;
        }
        $this->isOpenCheckoutModal = true;
    }

    public function closeCheckoutModal()
    {
        $this->isOpenCheckoutModal = false;
    }

    public function confirmCheckout()
    {
        $user = auth()->user();
        $cart = session('cart', []);

        if (empty($cart)) {
            $this->closeCheckoutModal();
            return;
        }

        $packages = Package::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $enrolledNames = [];
        $totalItemsCount = 0;

        foreach ($cart as $packageId => $qty) {
            if (isset($packages[$packageId])) {
                $pkg = $packages[$packageId];
                
                // Attach package multiple times based on quantity
                for ($i = 0; $i < $qty; $i++) {
                    $user->packages()->attach($pkg->id, [
                        'start_date' => Carbon::today()->format('Y-m-d'),
                        'duration_weeks' => $pkg->duration_weeks ?: 40,
                    ]);
                }
                
                $enrolledNames[] = "'{$pkg->name}' ({$qty} unit)";
                $totalItemsCount += $qty;
            }
        }

        // Clear cart
        session()->forget('cart');
        $this->dispatch('cart-updated');

        // Create dynamic notification
        $namesStr = implode(', ', $enrolledNames);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Pendaftaran Paket Berhasil 🎉',
            'message' => "Selamat! Anda berhasil terdaftar di paket: {$namesStr} dengan total akumulasi setoran Rp " . number_format($this->totalWeeklyInstallment, 0, ',', '.') . " per minggu. Silakan mulai melakukan transfer dan unggah bukti transfer.",
            'type' => 'catalog_checkout',
            'is_read' => false,
        ]);

        session()->flash('message', "Checkout berhasil! Anda sekarang terdaftar di {$totalItemsCount} paket baru.");
        $this->closeCheckoutModal();
        return $this->redirect(route('customer.my-package'), navigate: true);
    }

    public function render()
    {
        return view('livewire.customer.cart')->layout('layouts.app');
    }
}
