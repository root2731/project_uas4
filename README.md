Link VIDIO YouTube : https://youtu.be/-SWe84FyggY

# 📦 StoreManagerPro — Aplikasi Manajemen Toko (UAS Web Dinamis)

StoreManagerPro adalah aplikasi web dinamis berbasis PHP dan MySQL yang dirancang untuk mengelola inventaris, transaksi, dan pengguna dalam sebuah toko. Aplikasi ini mendukung autentikasi pengguna, manajemen produk, keranjang belanja, laporan transaksi, dan banyak fitur lainnya.

---

## 📁 Struktur Folder

```plaintext
PROJECT-UAS/
│
├── docs/                      # Dokumentasi instalasi dan penggunaan
│   ├── INSTALLATION.md        # Panduan instalasi
│   └── USAGE.md               # Panduan penggunaan aplikasi
│
├── sql/
│   └── database.sql           # Skrip pembuatan dan pengisian database
│
├── src/
│   ├── assets/css/
│   │   └── style.css          # File CSS untuk tampilan UI
│   │
│   ├── config/
│   │   └── database.php       # Koneksi database MySQL
│   │
│   ├── includes/
│   │   ├── auth.php           # Fungsi autentikasi dan session
│   │   └── functions.php      # Fungsi utilitas umum
│   │
│   ├── pages/                 # Halaman utama aplikasi
│   │   ├── cart_add.php           # Tambah produk ke keranjang
│   │   ├── cart.php               # Lihat isi keranjang belanja
│   │   ├── categories.php         # Manajemen kategori produk
│   │   ├── checkout.php           # Proses checkout transaksi
│   │   ├── dashboard.php          # Beranda admin/staff
│   │   ├── my_orders.php          # Riwayat transaksi user
│   │   ├── products.php           # Manajemen produk
│   │   ├── products_public.php    # Halaman produk publik (untuk customer)
│   │   ├── profile.php            # Lihat dan edit profil pengguna
│   │   ├── reports.php            # Laporan penjualan dan aktivitas
│   │   ├── settings.php           # Pengaturan toko (nama, pajak, dll.)
│   │   ├── transaction_detail.php # Detail transaksi
│   │   ├── transactions.php       # Manajemen transaksi
│   │   └── users.php              # Manajemen pengguna/admin
│
│   ├── index.php              # Halaman utama (routing/logika tampilan)
│   ├── login.php              # Halaman login
│   └── logout.php             # Proses logout

🛠️ Fitur Utama
- Autentikasi pengguna (admin, staff, customer)
- Manajemen produk & kategori
- Keranjang belanja & checkout
- Riwayat dan detail transaksi
- Laporan dan analisis penjualan
- Pengaturan toko
- Pengelolaan pengguna dan profil
- Dashboard informatif

🧱 Skema Database
- users: Menyimpan data akun pengguna
- user_profiles: Detail profil pengguna
- categories: Kategori produk
- products: Daftar produk
- carts: Isi keranjang pengguna
- transactions: Riwayat transaksi
- transaction_details: Rincian produk dalam transaksi
- payments: Informasi pembayaran
- activity_logs: Log aktivitas pengguna
- settings: Pengaturan toko (nama, pajak, mata uang)

📂 Instalasi
Lihat docs/INSTALLATION.md untuk panduan lengkap instalasi lokal menggunakan XAMPP atau Laragon.

▶️ Cara Menjalankan
- Clone repositori
- Import database.sql ke MySQL
- Atur koneksi di src/config/database.php
- Jalankan aplikasi melalui http://localhost/project-uas/src/login.php
- Jalankan aplikasi melalui online : https://storemanagerpro.my.id/src/login.php
