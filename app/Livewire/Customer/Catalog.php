<?php

namespace App\Livewire\Customer;

use App\Models\Package;
use App\Models\Notification;
use Livewire\Component;

class Catalog extends Component
{
    public function addToCart($id)
    {
        $package = Package::where('status', 'active')->findOrFail($id);
        
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        session(['cart' => $cart]);
        
        $this->dispatch('cart-updated');
        session()->flash('message', "Paket '{$package->name}' berhasil ditambahkan ke keranjang!");
    }

    public function render()
    {
        $packages = Package::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $followedPackages = auth()->user()->packages->groupBy('id')->map(function ($items) {
            return $items->count();
        })->toArray();

        return view('livewire.customer.catalog', [
            'packages' => $packages,
            'followedPackages' => $followedPackages,
        ])->layout('layouts.app');
    }
}
