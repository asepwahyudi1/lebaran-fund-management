<?php

namespace App\Livewire\Admin;

use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Packages extends Component
{
    use WithFileUploads;

    public $name = '';
    public $price = '';
    public $description = '';
    public $status = 'active';
    public $duration_weeks = 40;
    public $image;
    public $existingImage;

    public $isEditing = false;
    public $editingId = null;
    public $isOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'duration_weeks' => 'required|integer|min:1',
        'image' => 'nullable|image|max:2048', // 2MB max
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
        $this->duration_weeks = 40;
        $this->image = null;
        $this->existingImage = null;
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'status' => $this->status,
            'duration_weeks' => $this->duration_weeks,
        ];

        if ($this->image) {
            // Delete old image if it exists
            if ($this->editingId) {
                $oldPkg = Package::find($this->editingId);
                if ($oldPkg && $oldPkg->image_path) {
                    Storage::disk('public')->delete($oldPkg->image_path);
                }
            }

            // Save new image
            $data['image_path'] = $this->image->store('packages', 'public');
        }

        Package::updateOrCreate(
            ['id' => $this->editingId],
            $data
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
        $this->duration_weeks = $package->duration_weeks;
        $this->existingImage = $package->image_path;
        $this->image = null;
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

        // Delete associated image file
        if ($package->image_path) {
            Storage::disk('public')->delete($package->image_path);
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
