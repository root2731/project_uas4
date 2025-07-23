# 🧭 Panduan Penggunaan StoreManagerPro

Dokumen ini menjelaskan alur penggunaan aplikasi StoreManagerPro untuk **Admin**, **Staff**, dan **Customer**.

---

## 👤 Login

Akses melalui:
http://localhost/project-uas/src/login.php / https://storemanagerpro.my.id/src/index.php?page=reports


Masukkan username dan password sesuai dengan role Anda.

---

## 🧑‍💼 Admin

### 1. Dashboard
Menampilkan ringkasan jumlah produk, transaksi, dan user.

### 2. Produk
- Tambah, edit, dan hapus produk.
- Kelola stok dan harga.
- Upload gambar produk.

### 3. Kategori
Kelola kategori produk.

### 4. Transaksi
- Lihat daftar transaksi
- Lihat detail pesanan (barang, total, status)

### 5. Laporan
- Laporan penjualan harian/bulanan
- Unduh MS EXCEL

### 6. Pengguna
- Tambah user baru (admin/staff/customer)
- Ubah hak akses

### 7. Pengaturan
- Nama toko
- Logo
- Mata uang
- Pajak (PPN)

---

## 🧑 Customer

### 1. Lihat Produk
Melalui `products_public.php` pelanggan bisa melihat produk yang tersedia.

### 2. Tambah ke Keranjang
Klik tombol "Tambah ke Keranjang" → `cart_add.php`.

### 3. Lihat Keranjang
Halaman `cart.php` akan menampilkan produk yang siap dibeli.

### 4. Checkout
Pilih produk yang ingin dibayar → lanjut ke `checkout.php`.

### 5. Riwayat Pembelian
Cek transaksi yang sudah dilakukan di `my_orders.php`.

---

## 📝 Catatan

- Logout tersedia di `logout.php`
- Hak akses dibatasi menggunakan peran (role)
- Admin bisa melihat semua transaksi dan user
- Customer hanya melihat miliknya sendiri

---

