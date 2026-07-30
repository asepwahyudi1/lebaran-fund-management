<?php

namespace App\Livewire\Admin;

use App\Models\Package;
use Livewire\Attributes\On;
use Livewire\Component;

class Packages extends Component
{
    public $name = '';
    public $price = '';
    public $description = '';
    public $status = 'active';

    public $isEditing = false;
    public $editingId = null;
    public $isOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
    ];

    #[On('open-package-modal-event')]
    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->price = '';
        $this->description = '';
        $this->status = 'active';
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        Package::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'status' => $this->status,
            ]
        );

        session()->flash('message', $this->isEditing ? 'Paket berhasil diperbarui.' : 'Paket berhasil ditambahkan.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $this->editingId = $id;
        $this->name = $package->name;
        $this->price = $package->price;
        $this->description = $package->description;
        $this->status = $package->status;
        $this->isEditing = true;
        $this->isOpen = true;
    }

    public function toggleStatus($id)
    {
        $package = Package::findOrFail($id);
        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();
        session()->flash('message', 'Status paket berhasil diubah.');
    }

    public function delete($id)
    {
        $package = Package::findOrFail($id);
        // Optional check: is anyone using this package?
        if ($package->users()->where('role', 'customer')->count() > 0) {
            session()->flash('error', 'Paket tidak dapat dihapus karena sedang digunakan oleh pelanggan.');
            return;
        }
        
        $package->delete();
        session()->flash('message', 'Paket berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.packages', [
            'packagesList' => Package::orderBy('created_at', 'desc')->get(),
        ])->layout('layouts.app');
    }
}
