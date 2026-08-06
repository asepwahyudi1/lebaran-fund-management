<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin Sumber Sari',
            'email' => 'admin@sumbersari.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone_number' => '081234567890',
            'address' => 'Kantor UMKM Sumber Sari, Jakarta',
            'start_date' => now(),
        ]);

        // 2. Create Packages
        $packageA = Package::create([
            'name' => 'Paket Sembako Hemat',
            'price' => 1200000,
            'description' => 'Beras 10kg, Minyak Goreng 5L, Gula 5kg, Terigu 5kg, Sirup 2 botol, Kopi, Teh, Mie Instan 1 dus.',
            'status' => 'active',
            'duration_weeks' => 40,
        ]);

        $packageB = Package::create([
            'name' => 'Paket Sembako Premium',
            'price' => 2400000,
            'description' => 'Beras Premium 20kg, Minyak Goreng 10L, Gula 10kg, Terigu 10kg, Sirup 4 botol, Biscuit 2 kaleng, Daging Sapi 2kg (voucher).',
            'status' => 'active',
            'duration_weeks' => 40,
        ]);

        $packageC = Package::create([
            'name' => 'Paket Kue Lebaran',
            'price' => 800000,
            'description' => 'Nastar, Kastengel, Putri Salju, Sagu Keju, Kacang Bawang, Emping Melinjo.',
            'status' => 'active',
            'duration_weeks' => 40,
        ]);

        // 3. Create Customers
        $customerA = User::create([
            'name' => 'Ahmad Hidayat',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '085711122233',
            'address' => 'Jl. Raya Bojong Gede No. 45, Bogor',
        ]);
        $customerA->packages()->attach($packageA->id, [
            'start_date' => now()->subWeeks(3)->format('Y-m-d'),
            'duration_weeks' => 40,
        ]);

        $customerB = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '081988877766',
            'address' => 'Perum Sari Gading Blok C4, Depok',
        ]);
        $customerB->packages()->attach($packageB->id, [
            'start_date' => now()->subWeeks(10)->format('Y-m-d'),
            'duration_weeks' => 40,
        ]);

        $customerC = User::create([
            'name' => 'Budi Prasetyo',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '081299988877',
            'address' => 'Kampung Baru RT 03/05, Citayam',
        ]);
        $customerC->packages()->attach($packageC->id, [
            'start_date' => now()->format('Y-m-d'),
            'duration_weeks' => 40,
        ]);

        $customerD = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '087812345678',
            'address' => 'Jl. Kenanga No. 12, Cibinong',
        ]);
        $customerD->packages()->attach($packageA->id, [
            'start_date' => now()->subWeeks(5)->format('Y-m-d'),
            'duration_weeks' => 40,
        ]);

        // Seed payments for Ahmad Hidayat (3 verified weeks of Rp 30.000)
        for ($i = 1; $i <= 3; $i++) {
            Payment::create([
                'user_id' => $customerA->id,
                'amount' => 30000,
                'payment_date' => now()->subWeeks(4 - $i),
                'payment_method' => 'manual',
                'status' => 'verified',
                'verified_at' => now()->subWeeks(4 - $i),
                'admin_notes' => "Setoran Tabungan Minggu ke-$i (Manual oleh Admin)",
            ]);
        }

        // Seed payments for Siti Rahma (6 verified weeks, 1 pending week of Rp 60.000)
        for ($i = 1; $i <= 6; $i++) {
            Payment::create([
                'user_id' => $customerB->id,
                'amount' => 60000,
                'payment_date' => now()->subWeeks(11 - $i),
                'payment_method' => 'manual',
                'status' => 'verified',
                'verified_at' => now()->subWeeks(11 - $i),
                'admin_notes' => "Setoran Tabungan Minggu ke-$i (Manual oleh Admin)",
            ]);
        }

        Payment::create([
            'user_id' => $customerB->id,
            'amount' => 60000,
            'payment_date' => now(),
            'payment_method' => 'transfer',
            'status' => 'pending',
            'admin_notes' => 'Setoran Tabungan Minggu ke-7 (Transfer Bank)',
        ]);
    }
}
