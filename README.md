# Sistem Manajemen User dan Produk Gudang

Sistem web berbasis PHP untuk mengelola pengguna dan produk di gudang. Sistem ini menyediakan fitur registrasi, login, manajemen profil, dan pengelolaan produk dengan antarmuka admin yang sederhana.

## Fitur Utama

### Manajemen Pengguna
- Registrasi akun baru dengan aktivasi email
- Login dan logout
- Reset password melalui email
- Ubah password
- Manajemen profil
- Sistem role (admin_gudang, user)

### Manajemen Produk
- Tambah produk baru
- Edit produk
- Hapus produk
- Lihat daftar produk dengan informasi harga dan stok

### Fitur Tambahan
- Notifikasi email untuk registrasi baru
- Dashboard admin dengan tampilan yang responsif
- Validasi input dan keamanan dasar
- Export database (SQL dump)

## Prasyarat

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Composer (untuk mengelola dependensi PHP)
- Server web (Apache/Nginx) atau XAMPP
- Akun Gmail untuk pengiriman email (dengan App Password)

## Instalasi

1. **Clone atau download proyek ini** ke direktori web server Anda (misalnya `htdocs` di XAMPP).

2. **Install dependensi PHP** menggunakan Composer:
   ```
   composer install
   ```

3. **Konfigurasi database**:
   - Buat database baru di MySQL dengan nama `user_management`
   - Import file `export_db.sql` ke database tersebut

4. **Konfigurasi email**:
   - Edit file `functions.php`
   - Ganti `Username` dan `Password` dengan email Gmail dan App Password Anda
   - Pastikan App Password diaktifkan di akun Gmail Anda

5. **Jalankan aplikasi**:
   - Akses melalui browser: `http://localhost/user_management/`
   - Halaman utama: `login.php`

## Setup Database

Jalankan query berikut di MySQL untuk membuat database dan tabel:

```sql
-- Buat database
CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE user_management;

-- Tabel users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(150),
  role ENUM('admin_gudang','user') DEFAULT 'admin_gudang',
  is_active TINYINT(1) DEFAULT 0,
  activation_token VARCHAR(255),
  reset_token VARCHAR(255),
  reset_expires DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel products
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) DEFAULT 0,
  stock INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Penggunaan

1. **Registrasi**: Akses `register.php` untuk membuat akun baru
2. **Aktivasi**: Klik link aktivasi yang dikirim ke email
3. **Login**: Masuk menggunakan email dan password di `login.php`
4. **Dashboard**: Kelola produk dan profil pengguna
5. **Manajemen Produk**: Tambah, edit, atau hapus produk melalui dashboard

## Struktur File

```
user_management/
├── activate.php          # Aktivasi akun
├── change_password.php   # Ubah password
├── composer.json         # Dependensi PHP
├── composer.lock         # Lock file Composer
├── dashboard.php         # Dashboard admin
├── db.php                # Koneksi database
├── export_db.sql         # Dump database
├── forgot.php            # Lupa password
├── functions.php         # Fungsi utilitas dan email
├── login.php             # Halaman login
├── logout.php            # Logout
├── products_add.php      # Tambah produk
├── products_delete.php   # Hapus produk
├── products_edit.php     # Edit produk
├── profile.php           # Profil pengguna
├── register.php          # Registrasi
├── reset.php             # Reset password
└── css/
    └── style.css         # Styling CSS
```

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Email**: PHPMailer
- **Frontend**: HTML, CSS
- **Dependency Management**: Composer

## Keamanan

- Password di-hash menggunakan bcrypt
- Validasi input untuk mencegah SQL injection
- Session management untuk autentikasi
- Token-based activation dan reset password

## Kontribusi

Untuk berkontribusi pada proyek ini:
1. Fork repositori
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.

## Dukungan

Jika Anda mengalami masalah atau memiliki pertanyaan, silakan buat issue di repositori ini.
