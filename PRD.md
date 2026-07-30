# Product Requirements Document (PRD)

# Aplikasi Pengelolaan Dana Paket Lebaran Berbasis Web

Version: 1.0
Status: Draft

---

# 1. Overview

## Product Name

Aplikasi Pengelolaan Dana Paket Lebaran Berbasis Web

## Background

UMKM Sumber Sari merupakan usaha yang bergerak di bidang penjualan paket kebutuhan Lebaran dengan sistem pembayaran secara cicilan. Saat ini seluruh proses administrasi, mulai dari pencatatan pelanggan, pembayaran cicilan, hingga penyusunan laporan masih dilakukan secara manual menggunakan buku dan spreadsheet. Hal tersebut menyebabkan proses pengelolaan data menjadi kurang efisien, berisiko terjadi kesalahan pencatatan, serta menyulitkan dalam proses pencarian data dan pembuatan laporan.

Untuk mengatasi permasalahan tersebut diperlukan sebuah aplikasi berbasis web yang mampu mengelola seluruh proses pembayaran paket Lebaran secara terintegrasi sehingga meningkatkan efisiensi, akurasi, dan transparansi dalam pengelolaan data.

---

# 2. Objectives

Aplikasi ini bertujuan untuk:

- Mengelola data pelanggan secara terstruktur.
- Mengelola data paket Lebaran.
- Mencatat pembayaran cicilan pelanggan.
- Memfasilitasi pelanggan mengunggah bukti transfer.
- Memudahkan admin melakukan verifikasi pembayaran.
- Menyediakan laporan pembayaran secara otomatis.
- Mengurangi kesalahan pencatatan data.

---

# 3. User Roles

## Admin

Hak akses:

- Login
- Mengelola pelanggan
- Mengelola paket Lebaran
- Mengelola pembayaran
- Memverifikasi bukti transfer
- Melihat laporan
- Mengelola akun pelanggan

---

## Pelanggan

Hak akses:

- Login
- Melihat informasi paket
- Melihat riwayat pembayaran
- Mengunggah bukti transfer
- Melihat progres cicilan

---

# 4. Scope

## In Scope

### Authentication

- Login
- Logout

### Customer Management

- Tambah pelanggan
- Edit pelanggan
- Hapus pelanggan
- Detail pelanggan
- Daftar pelanggan

### Package Management

- Tambah paket
- Edit paket
- Hapus paket
- Daftar paket
- Harga paket
- Deskripsi paket

### Installment Management

- Input pembayaran
- Riwayat pembayaran
- Status pembayaran
- Total pembayaran
- Sisa cicilan

### Payment Verification

- Upload bukti transfer
- Verifikasi pembayaran
- Tolak pembayaran
- Catatan admin

### Reporting

- Laporan pembayaran
- Laporan pelanggan
- Rekap transaksi

---

# 5. Out of Scope

Fitur berikut tidak termasuk dalam pengembangan:

- Payment Gateway
- Integrasi Bank
- Notifikasi WhatsApp
- Mobile Application
- Multi Cabang
- Sistem Akuntansi
- Integrasi Marketplace

---

# 6. Functional Requirements

## Dashboard

Admin dapat melihat:

- Total Pelanggan
- Total Paket
- Total Pembayaran
- Total Pendapatan
- Pembayaran Menunggu Verifikasi
- Pembayaran Hari Ini
- Pelanggan Baru
- Aktivitas Terbaru

---

## Customer Module

Admin dapat:

- Menambahkan pelanggan
- Mengubah data pelanggan
- Menghapus pelanggan
- Melihat detail pelanggan
- Mencari pelanggan
- Search pelanggan
- Filter pelanggan
- Pagination

Data pelanggan meliputi:

- Nama
- Nomor Telepon
- Alamat
- Paket yang dipilih

---

## Package Module

Admin dapat:

- Menambah paket
- Mengubah paket
- Menghapus paket

Data paket:

- Nama Paket
- Harga
- Deskripsi

Status Paket

- Aktif
- Tidak Aktif

---

## Payment Module

Admin dapat:

- Menambahkan pembayaran
- Melihat histori pembayaran
- Mengubah status pembayaran

Data pembayaran:

- Nominal
- Tanggal
- Metode Transfer
- Bukti Transfer
- Status

Progress Cicilan

- Total Cicilan
- Sudah Dibayar
- Sisa Pembayaran

---

## Verification Module

Admin melakukan:

- Melihat bukti transfer
- Menyetujui pembayaran
- Menolak pembayaran
- Memberikan catatan
- Preview gambar bukti transfer

Status:

- Pending
- Verified
- Rejected

---

## Customer Portal

Pelanggan dapat:

- Login
- Melihat data paket
- Melihat progres cicilan
- Mengunggah bukti pembayaran
- Melihat status verifikasi
- Download bukti pembayaran

---

# 7. Non Functional Requirements

## Performance

- Waktu respon < 3 detik
- Mendukung minimal 50 pengguna aktif

## Security

- Password terenkripsi
- Session Login
- Validasi Input
- Role Based Access

## Usability

- Antarmuka sederhana
- Mudah dipahami
- Responsive Desktop

---

# 8. Business Flow

1. Admin login ke dalam sistem.
2. Admin membuat atau mengelola data paket Lebaran.
3. Admin mendaftarkan data pelanggan.
4. Admin menghubungkan pelanggan dengan paket yang dipilih.
5. Pelanggan melakukan pembayaran melalui transfer bank.
6. Pelanggan mengunggah bukti pembayaran melalui sistem.
7. Admin melakukan verifikasi bukti pembayaran.
8. Sistem memperbarui status pembayaran dan progres cicilan secara otomatis.
9. Admin memantau data pembayaran dan mencetak laporan.

---

# 9. Technology Stack

## Frontend

- Laravel Blade
- Livewire 3
- Flux UI
- Tailwind CSS v4
- Alpine.js

## Backend

- Laravel 12

## Database

- MySQL

## Storage

- Local Storage

## Authentication

- Laravel Authentication (Session Based)

---

## UI Components

- Flux UI

---

# 10. Success Criteria

Aplikasi dinyatakan berhasil apabila:

- Seluruh data pelanggan dapat dikelola.
- Pembayaran cicilan dapat dicatat.
- Bukti transfer dapat diverifikasi.
- Laporan pembayaran dapat dihasilkan.
- Proses administrasi menjadi lebih cepat dibanding pencatatan manual.
- Risiko kesalahan pencatatan dapat diminimalkan.

---

# 11. Future Enhancement

Pengembangan selanjutnya dapat mencakup:

- Payment Gateway
- Email Notification
- WhatsApp Notification
- Dashboard Analytics
- Export PDF
- Export Excel
- Multi User Management
- Backup Database
