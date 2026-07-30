<?php

namespace App\Livewire\Admin;

use App\Models\Package;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPackageId = '';

    // Form fields
    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $address = '';
    public $package_id = '';
    public $password = '';

    // Modal controls
    public $isOpen = false;
    public $isEditing = false;
    public $editingId = null;

    // Detail Modal controls
    public $isOpenDetail = false;
    public $detailCustomerId = null;
    public $detailCustomer = null;
    public $detailPayments = [];
    public $totalPaid = 0;
    public $remainingBalance = 0;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingId,
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $this->editingId,
            'address' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'password' => $this->isEditing ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPackageId()
    {
        $this->resetPage();
    }

    #[On('open-customer-modal-event')]
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
        $this->email = '';
        $this->phone_number = '';
        $this->address = '';
        $this->package_id = '';
        $this->password = '';
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'package_id' => $this->package_id,
            'role' => 'customer',
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(
            ['id' => $this->editingId],
            $data
        );

        session()->flash('message', $this->isEditing ? 'Pelanggan berhasil diperbarui.' : 'Pelanggan berhasil ditambahkan.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->package_id = $customer->package_id;
        $this->password = ''; // leave blank for editing
        $this->isEditing = true;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();
        session()->flash('message', 'Pelanggan berhasil dihapus.');
    }

    public function showDetail($id)
    {
        $this->detailCustomerId = $id;
        $this->detailCustomer = User::with('package')->findOrFail($id);
        
        // Fetch all verified payments for calculations
        $this->detailPayments = Payment::where('user_id', $id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $this->totalPaid = Payment::where('user_id', $id)
            ->where('status', 'verified')
            ->sum('amount');

        $packagePrice = $this->detailCustomer->package->price ?? 0;
        $this->remainingBalance = max(0, $packagePrice - $this->totalPaid);

        $this->isOpenDetail = true;
    }

    public function closeDetail()
    {
        $this->isOpenDetail = false;
        $this->detailCustomer = null;
        $this->detailPayments = [];
    }

    public function render()
    {
        $query = User::where('role', 'customer')->with('package');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterPackageId) {
            $query->where('package_id', $this->filterPackageId);
        }

        return view('livewire.admin.customers', [
            'customers' => $query->orderBy('created_at', 'desc')->paginate(10),
            'packages' => Package::where('status', 'active')->get(),
        ])->layout('layouts.app');
    }
}
