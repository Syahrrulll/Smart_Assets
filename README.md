# Dokumentasi Smart Asset Management System

Sistem Manajemen Aset Cerdas (*Smart Asset Management*) adalah aplikasi enterprise berbasis web (Laravel & Tailwind CSS) yang dirancang untuk mengelola siklus hidup aset fisik di sebuah organisasi atau perusahaan, mulai dari pembelian, pencatatan, peminjaman, perawatan (maintenance), pemindahan, hingga penghapusan (disposal).

Aplikasi ini menggunakan konsep **Role-Based Access Control (RBAC)** dengan antarmuka **Glassmorphism Premium** yang interaktif, responsif, dan kaya akan fitur analitik keuangan (seperti depresiasi).

---

## 👥 Aktor & Hak Akses (RBAC)

Sistem membagi pengguna ke dalam dua peran utama:
1. **Administrator (`admin`)**
   - Memiliki kontrol penuh atas seluruh sistem.
   - Dapat menambah, mengedit, dan menghapus barang secara penuh.
   - Dapat menyetujui pemindahan barang (*Relocation*) dan penghapusan aset (*Disposal*).
   - Mengelola master data pengguna dan lokasi.
   - Dapat mengunduh file *Backup Database*.
2. **Staf (`staff`)**
   - Dapat melihat daftar barang dan mencetak QR Code.
   - Dapat meminjam barang (*Checkout*) dan mengembalikannya (*Checkin*).
   - Dapat melaporkan kerusakan aset (*Maintenance Ticket*).
   - Dapat mengajukan pemindahan atau penghapusan barang (menunggu persetujuan Admin).
   - Hanya dapat melakukan pengeditan terbatas (misal: *request* pembaruan, tidak dapat mengubah nilai pembelian atau tahun beli).

---

## 🌟 Modul & Fitur Utama

### 1. Dasbor & Manajemen Aset Inti (Inventory)
- **Pencatatan QR Code:** Setiap aset yang didaftarkan memiliki QR Code unik yang dapat dicetak secara massal dan ditempelkan pada fisik barang. Pemindaian QR Code dari *smartphone* akan langsung membuka detail aset tersebut.
- **Pencarian Cerdas & Filter:** Mencari aset berdasarkan nama, lokasi, tahun pembelian, atau nomor NUP. Kolom pencarian _dropdown_ (menggunakan TomSelect) memudahkan pencarian daftar panjang.
- **Nilai Penyusutan (Depreciation Tracking):** Menggunakan metode **Garis Lurus (Straight-line)**. Sistem otomatis menghitung *Nilai Buku* (Book Value) saat ini berdasarkan `Harga Beli`, `Umur Ekonomis`, dan `Tahun Pembelian`.
- **Riwayat Perubahan (Audit Trail):** Setiap perubahan data yang dilakukan pada suatu aset akan dicatat di database (Siapa yang mengubah, apa nilai lama, apa nilai baru).

### 2. Siklus Hidup Aset (Asset Lifecycle)

Aplikasi memiliki 4 alur kerja (workflow) terpisah di sidebar untuk mengelola siklus pergerakan aset:

#### A. Peminjaman (Checkout / Checkin)
- **Fungsi:** Mencatat siapa yang sedang meminjam atau membawa barang kantor (seperti laptop, kamera, proyektor).
- **Alur Kerja:** 
  1. Staf membuka menu Peminjaman.
  2. Memilih barang, memasukkan nama peminjam, dan target tanggal kembali.
  3. Status aset menjadi `Borrowed`.
  4. Ketika dikembalikan, staf mengklik tombol "Selesai", mencatat waktu pengembalian, dan status kembali menjadi `Returned`.

#### B. Perbaikan (Maintenance Ticket)
- **Fungsi:** Pelaporan kerusakan dan pelacakan riwayat servis (Preventive & Reactive Maintenance).
- **Fitur Cerdas (Preventive):** Sistem dapat mendeteksi "Interval Perawatan" (misal: tiap 6 bulan). Dasbor memiliki widget khusus yang menyala apabila ada aset yang jatuh tempo untuk diservis, dan *Command Line* `php artisan asset:check-maintenance` otomatis menerbitkan tiket servis baru.
- **Alur Kerja:**
  1. Pengguna melaporkan masalah/kerusakan pada aset.
  2. Status awal adalah `Open`.
  3. Teknisi mulai bekerja (Status: `In Progress`).
  4. Perbaikan selesai, teknisi/admin mengisi *Biaya Perbaikan* (Cost) dan menutup tiket (Status: `Resolved`).

#### C. Pemindahan (Approval Relocation)
- **Fungsi:** Memantau perpindahan aset dari satu lokasi ke lokasi lain.
- **Alur Kerja:**
  1. Staf mengajukan pemindahan aset (misal: dari Gudang A ke Ruang Rapat).
  2. Status permohonan adalah `Pending`.
  3. Admin meninjau permohonan. Admin dapat melakukan `Approve` (yang otomatis mengubah kolom Lokasi pada data aset utama) atau `Reject`.

#### D. Penghapusan (Disposal Workflow)
- **Fungsi:** Prosedur resmi pembuangan/penghapusan aset yang sudah rusak berat, usang, atau habis umur ekonomisnya.
- **Alur Kerja:**
  1. Staf/Admin mengajukan penghapusan beserta alasan (misal: "Terbakar" atau "Mati Total").
  2. Status berada di `Pending`.
  3. Administrator menyetujui pembuangan (`Approved`).
  4. Aset ditandai sebagai `Disposed` dan dapat dikeluarkan dari sirkulasi laporan aktif.

---

## ⚙️ Fitur Enterprise Tambahan

### 1. Optimalisasi Penyimpanan (Image Compression)
Sistem menggunakan `intervention/image` untuk otomatis mengubah ukuran (*resize*) dan mengompres (*compress*) setiap foto aset yang diunggah (Maksimal 1024px dengan kualitas 70%). Ini memastikan server tidak cepat penuh meskipun ribuan foto diunggah dari *smartphone*.

### 2. Backup Otomatis & Pemulihan (Disaster Recovery)
Sistem dilengkapi modul `spatie/laravel-backup` untuk mem-backup foto (public) dan database (`inventaris.sqlite` / MySQL). 
- **Tombol Unduh UI:** Administrator dapat langsung mengklik "Unduh Backup" dari sudut sidebar bawah untuk mengamankan data lokal secara *zip* tanpa harus membuka terminal server.

### 3. Progressive Web App (PWA) & Mobile Responsive
Sistem menggunakan manifest `manifest.json` dan didesain responsif untuk layar kecil (Mobile), sehingga staf gudang dapat berjalan membawa *smartphone* atau tablet sambil memindai QR Code di lapangan dengan lancar.

---

## 🛠️ Stack Teknologi

- **Backend:** PHP 8.x, Laravel 11.x
- **Database:** MySQL / SQLite
- **Frontend CSS:** Tailwind CSS (via CDN / Vite)
- **Frontend JS:** Alpine.js (untuk interaksi mikro), TomSelect (untuk *searchable dropdown*)
- **Ikon & Tipografi:** FontAwesome 6, Google Fonts (Outfit & Inter)
- **Dependencies Utama:** `simplesoftwareio/simple-qrcode`, `intervention/image`, `spatie/laravel-backup`, `barryvdh/laravel-dompdf`.

---

## 🔄 Contoh Siklus Penuh Aset

1. **Pembelian:** Admin membeli "Laptop Asus" (Rp 20 Juta). Admin menambahkannya ke sistem, mengatur umur ekonomis (5 tahun), dan interval maintenance (6 bulan).
2. **Labeling:** Admin menempelkan QR Code hasil cetak ke punggung laptop.
3. **Peminjaman:** Budi meminjam Laptop tersebut ke luar kota. Staf mencatat *Checkout* di sistem.
4. **Maintenance:** Bulan ke-6, sistem otomatis menyalakan peringatan *Maintenance* di Dasbor. Teknisi melakukan pembersihan debu laptop (Rp 100 Ribu) dan menandai tiket sebagai *Resolved*.
5. **Pemindahan:** Setahun kemudian, laptop dipindahkan ke Ruang Direktur. Staf membuat *Relocation Request*, dan Admin menyetujuinya.
7. **Penghapusan:** Tahun ke-6, laptop mati total. Staf membuat *Disposal Request*. Admin mengecek, dan melegalkan penghapusan aset tersebut dari inventaris aktif.

---

## 🚀 Cara Instalasi & Menjalankan Aplikasi

Berikut adalah langkah-langkah untuk menginstal dan menjalankan aplikasi Smart Asset Management di sistem operasi Windows (terutama jika menggunakan **Laragon** atau XAMPP).

### 1. Persiapan Lingkungan
Pastikan Anda sudah menginstal:
- **PHP** (Minimal versi 8.2)
- **Composer**
- **MySQL / MariaDB** (Sudah termasuk di dalam Laragon/XAMPP)
- **Node.js & NPM** (Opsional untuk frontend, meskipun sistem ini bisa menggunakan CDN Tailwind)

### 2. Kloning Repositori
Buka terminal Anda dan kloning proyek ini:
```bash
git clone https://github.com/Syahrrulll/Smart_Assets.git
cd Smart_Assets
```

### 3. Instalasi Dependensi
Jalankan perintah berikut untuk mengunduh semua pustaka PHP yang dibutuhkan:
```bash
composer install
```

### 4. Konfigurasi Environment (`.env`)
Salin file konfigurasi bawaan dan hasilkan kunci aplikasi (App Key):
```bash
cp .env.example .env
php artisan key:generate
```
Buka file `.env` di teks editor Anda, dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: Buatlah database kosong dengan nama `inventaris` di phpMyAdmin atau HeidiSQL milik Laragon).*

**Konfigurasi Khusus Windows (Untuk Backup)**
Jika Anda menggunakan Windows dan ingin fitur **Backup Database** berjalan lancar, Anda wajib menambahkan *path* mysqldump di akhir file `.env`:
```env
# Sesuaikan versi mysql Anda di folder Laragon
DUMP_BINARY_PATH='E:\Software\laragon\bin\mysql\mysql-8.4.3-winx64\bin'
```

### 5. Migrasi Database & Seeding (Data Awal)
Untuk membangun struktur tabel dan memuat akun administrator bawaan, jalankan:
```bash
php artisan migrate --seed
```

### 6. Tautkan Penyimpanan (Storage Link)
Agar foto-foto barang dan gambar profil bisa diakses oleh publik, jalankan:
```bash
php artisan storage:link
```

### 7. Menjalankan Server Lokal
Nyalakan server bawaan Laravel:
```bash
php artisan serve
```
Buka browser Anda dan kunjungi: **http://localhost:8000**

---

### 🔑 Akun Default (Login)
Jika Anda menggunakan seeder bawaan, gunakan kredensial berikut untuk masuk sebagai Admin:
- **Email:** `admin@admin.com`
- **Password:** `password`

*(Pastikan untuk mengganti kata sandi ini segera setelah berhasil masuk ke sistem di menu Pengaturan Pengguna).*
