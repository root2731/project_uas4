# 🧰 Panduan Instalasi StoreManagerPro

Ikuti langkah-langkah di bawah ini untuk menginstal dan menjalankan aplikasi StoreManagerPro secara lokal menggunakan XAMPP atau Laragon.

---

## 📌 Prasyarat

- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Web server lokal (XAMPP/Laragon)

---

## 🔧 Langkah Instalasi

### 1. Clone atau Unduh Proyek
bash git clone https://github.com/root2731/project_uas4.git

2. Salin ke Direktori Web
Salin folder PROJECT-UAS/ ke dalam:
- htdocs/ (jika menggunakan XAMPP)
- www/ (jika menggunakan Laragon)
Contoh : C:\xampp\htdocs\project-uas

3. Import Database
- Buka phpMyAdmin
- Buat database baru, misalnya: store_manager
- Klik Import
- Pilih file: sql/database.sql
- Jalankan import

4. Atur Koneksi Database
Edit file src/config/database.php:
$host = "localhost";
$user = "root";
$password = "";
$dbname = "store_manager";

5. Jalankan Aplikasi
Buka browser dan kunjungi:
http://localhost/project-uas/src/index.php

Login Awal (Contoh)
Role	Username	Password
Admin	admin	admin123
Customer	user	user123
Catatan: Password bisa saja sudah dalam bentuk hash tergantung isi database.sql.

❓ Masalah Umum
❗ White screen
Cek kembali koneksi database dan log error di xampp/apache/logs/error.log.

❗ Session tidak bekerja
Pastikan session_start(); dipanggil dan direktori penyimpanan session aktif di php.ini.

❗ CSS tidak muncul
Cek apakah path file style.css diakses dengan benar dan file disimpan di src/assets/css/.
