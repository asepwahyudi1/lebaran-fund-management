# Aplikasi Pengelolaan Dana Paket Lebaran - UMKM Sumber Sari

Aplikasi Pengelolaan Dana Paket Lebaran Berbasis Web merupakan sistem administrasi pengelolaan data pelanggan, pencatatan pembayaran cicilan, unggah bukti transfer, verifikasi pembayaran, serta penyusunan laporan otomatis untuk usaha paket Lebaran pada UMKM Sumber Sari.

---

## 🛠️ Prasyarat (Prerequisites)

Sebelum menjalankan aplikasi, pastikan komputer Anda sudah terinstal:

- **PHP** (minimal versi 8.2 atau lebih baru, direkomendasikan 8.3/8.4)
- **Composer** (untuk dependensi PHP)
- **Node.js & npm** (untuk aset frontend)
- Driver database **SQLite** (bawaan PHP)

---

## ⚙️ Langkah Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi dari awal di komputer lokal Anda:

### 1. Clone Repository

Unduh/clone repository ini ke komputer lokal Anda:

```bash
git clone https://github.com/asepwahyudi1/lebaran-fund-management.git
cd lebaran-fund-management
```

### 2. Instal Dependensi PHP (Composer)

Jalankan perintah berikut untuk menginstal package Laravel & Livewire:

```bash
composer install
```

### 3. Instal Dependensi Frontend (npm)

Jalankan perintah berikut untuk menginstal package CSS & Javascript:

```bash
npm install
```

### 4. Konfigurasi Environment File

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

### 5. Generate Application Key

Jalankan perintah generator key Laravel:

```bash
php artisan key:generate
```

### 6. Konfigurasi Database

Anda dapat memilih untuk menggunakan **SQLite** (default/praktis untuk dicoba) atau **MySQL** (XAMPP).

#### Opsi A: Menggunakan SQLite (Default)

Buat file database kosong (jika belum dibuat otomatis):

```bash
touch database/database.sqlite
```

_Pastikan di file `.env` Anda, variabel database diatur sebagai berikut:_

```env
DB_CONNECTION=sqlite
```

#### Opsi B: Menggunakan MySQL (XAMPP)

1. Buka XAMPP Control Panel, lalu jalankan (**Start**) service **Apache** dan **MySQL**.
2. Buka phpMyAdmin di browser Anda (`http://localhost/phpmyadmin`).
3. Buat database baru dengan nama **`db_lebaran_fund`**.
4. Buka file **`.env`** di project Anda, lalu ubah baris konfigurasi database menjadi seperti berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_lebaran_fund
DB_USERNAME=root
DB_PASSWORD=
```

_(Sesuaikan `DB_PASSWORD` jika MySQL XAMPP Anda memiliki password, biarkan kosong jika menggunakan default)._

### 7. Jalankan Migrasi & Seeder Database

Perintah ini akan membuat semua struktur tabel dan mengisi data awal (Admin, Paket, dan Pelanggan):

```bash
php artisan migrate:fresh --seed
```

### 8. Hubungkan Storage Link

Hubungkan folder unggahan agar bukti transfer gambar dapat diakses secara publik di browser:

```bash
php artisan storage:link
```

### 9. Build Aset Frontend

Lakukan kompilasi file Tailwind CSS dan Alpine.js:

```bash
# Untuk mode produksi (rekomendasi sekali jalan)
npm run build

# ATAU jalankan mode development (jika ingin mengubah kode frontend/css secara real-time)
npm run dev
```

---

## 🚀 Cara Menjalankan Aplikasi

1. Jalankan development server PHP:
    ```bash
    php artisan serve
    ```
2. Aplikasi akan berjalan di alamat: **`http://127.0.0.1:8000`**
3. Buka alamat tersebut pada browser Anda.

---

## 🔑 Akun Uji Coba (Pre-seeded Credentials)

Anda dapat langsung mencoba login menggunakan akun demo berikut:

### 👤 Peran: Admin (Pengelola)

- **Email:** `admin@sumbersari.com`
- **Password:** `admin123`
- **Hak Akses:** Kelola Pelanggan, Kelola Paket, Verifikasi Bukti Transfer (Approve/Reject), Manual Input Pembayaran, Cetak Laporan.

### 👥 Peran: Pelanggan (Customers)

Pengguna dapat login menggunakan **Email** maupun **Nomor Telepon**:

1. **Ahmad Hidayat**
    - **Email / Nomor Telepon:** `ahmad@gmail.com` atau `085711122233`
    - **Password:** `password`
    - **Paket:** Paket Sembako Hemat (Rp 1.200.000)

2. **Siti Rahma**
    - **Email / Nomor Telepon:** `siti@gmail.com` atau `081988877766`
    - **Password:** `password`
    - **Paket:** Paket Sembako Premium (Rp 2.400.000)

3. **Budi Prasetyo**
    - **Email / Nomor Telepon:** `budi@gmail.com` atau `081299988877`
    - **Password:** `password`
    - **Paket:** Paket Kue Lebaran (Rp 800.000)

---

## 🧪 Cara Menjalankan Pengujian (Testing)

Aplikasi ini sudah dilengkapi dengan unit & feature testing. Jalankan perintah berikut untuk memvalidasi seluruh alur kerja autentikasi dan otorisasi:

```bash
php artisan test
```

---

## 📊 Menampilkan & Mengekspor Diagram UML (PlantUML)

Seluruh diagram sistem (Use Case, Activity, Sequence, Deployment, dan ERD) telah dibuat menggunakan format teks **PlantUML** di dalam direktori **`docs/`**.

Berikut adalah panduan untuk menampilkan dan mengekspor diagram menjadi file gambar (PNG/SVG) di **VS Code (Windows)**:

### 1. Instalasi Ekstensi VS Code
1. Buka VS Code.
2. Klik menu **Extensions** di sebelah kiri (`Ctrl + Shift + X`).
3. Cari ekstensi bernama **`PlantUML`** (oleh *jebbs*) dan klik **Install**.

### 2. Konfigurasi Instan (Tanpa Perlu Instal Java & Graphviz Lokal)
Secara default, PlantUML membutuhkan Java dan Graphviz di komputer Anda. Namun, Anda dapat menggunakan server online publik bawaan agar diagram langsung ter-render secara instan:
1. Di VS Code, buka **Settings** (`Ctrl + ,`).
2. Cari kata kunci: **`plantuml server`**.
3. Di bagian **`Plantuml: Render`**, ubah nilainya dari `Local` menjadi **`PlantUMLServer`**.
4. Di bagian **`Plantuml: Server`**, pastikan nilainya adalah: `http://www.plantuml.com/plantuml` (ini adalah server resmi PlantUML).

### 3. Cara Menampilkan Preview Diagram
1. Buka salah satu file diagram di folder `docs/` (misalnya: [docs/erd.puml](file:///Users/asepwahyudi/Documents/web/lebaran-fund-management/docs/erd.puml)).
2. Tekan kombinasi tombol **`Alt + D`** pada keyboard Anda.
3. Panel preview gambar diagram akan muncul secara real-time di sisi kanan layar VS Code.

### 4. Cara Mengekspor Diagram ke File Gambar (PNG/SVG)
1. Pastikan Anda sedang membuka file `.puml` yang ingin diekspor.
2. Tekan tombol **`F1`** atau **`Ctrl + Shift + P`** pada keyboard untuk membuka *Command Palette*.
3. Ketik dan pilih perintah: **`PlantUML: Export Current Diagram`**.
4. Pilih format gambar yang Anda inginkan (misalnya: **`png`** atau **`svg`**).
5. File gambar hasil ekspor akan otomatis tersimpan di dalam subfolder **`docs/out/`** pada proyek Anda.
