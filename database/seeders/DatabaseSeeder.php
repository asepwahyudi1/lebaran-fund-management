<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
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
        ]);

        // 2. Create Packages
        $packageA = Package::create([
            'name' => 'Paket Sembako Hemat',
            'price' => 1200000,
            'description' => 'Beras 10kg, Minyak Goreng 5L, Gula 5kg, Terigu 5kg, Sirup 2 botol, Kopi, Teh, Mie Instan 1 dus.',
            'status' => 'active',
        ]);

        $packageB = Package::create([
            'name' => 'Paket Sembako Premium',
            'price' => 2400000,
            'description' => 'Beras Premium 20kg, Minyak Goreng 10L, Gula 10kg, Terigu 10kg, Sirup 4 botol, Biscuit 2 kaleng, Daging Sapi 2kg (voucher).',
            'status' => 'active',
        ]);

        $packageC = Package::create([
            'name' => 'Paket Kue Lebaran',
            'price' => 800000,
            'description' => 'Nastar, Kastengel, Putri Salju, Sagu Keju, Kacang Bawang, Emping Melinjo.',
            'status' => 'active',
        ]);

        // 3. Create Customers
        User::create([
            'name' => 'Ahmad Hidayat',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '085711122233',
            'address' => 'Jl. Raya Bojong Gede No. 45, Bogor',
            'package_id' => $packageA->id,
        ]);

        User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '081988877766',
            'address' => 'Perum Sari Gading Blok C4, Depok',
            'package_id' => $packageB->id,
        ]);

        User::create([
            'name' => 'Budi Prasetyo',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '081299988877',
            'address' => 'Kampung Baru RT 03/05, Citayam',
            'package_id' => $packageC->id,
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone_number' => '087812345678',
            'address' => 'Jl. Kenanga No. 12, Cibinong',
            'package_id' => $packageA->id,
        ]);
    }
}
