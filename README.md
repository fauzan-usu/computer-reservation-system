# 🏨✈️🌴 Computer Reservation System (CRS) - SMK Pariwisata

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

> **Sistem Reservasi Komputer untuk Pembelajaran SMK Pariwisata**  
> Hotel | Penerbangan | Paket Wisata

---

## 📋 Deskripsi Proyek

**CRS (Computer Reservation System)** adalah aplikasi web berbasis PHP dan MySQL yang dirancang khusus untuk siswa SMK Pariwisata dalam mempelajari sistem reservasi komputer. Aplikasi ini mencakup tiga modul utama:

- 🏨 **Hotel Reservation** - Reservasi kamar hotel
- ✈️ **Flight Reservation** - Pemesanan tiket penerbangan  
- 🌴 **Tour Package Reservation** - Pemesanan paket wisata

### ✨ Fitur Utama

| Modul | Fitur |
|-------|-------|
| **Admin** | Dashboard, Manajemen Hotel/Penerbangan/Paket, Lihat Semua Booking, Manajemen Customer |
| **Customer** | Registrasi, Login, Booking Hotel/Flight/Package, Riwayat Pesanan, Profil |
| **Umum** | Pencarian, Filter, Status Real-time, Format Rupiah, Responsive Design |

---

## 🚀 Cara Instalasi

### Prasyarat
- [XAMPP](https://www.apachefriends.org) (PHP 8.1+, MySQL, Apache)
- Web Browser (Chrome, Firefox, Edge)
- Git (opsional, untuk clone repository)

### Langkah Instalasi

#### 1. Clone atau Download Repository
```bash
git clone https://github.com/username/computer-reservation-system.git
```

#### 2. Pindahkan ke Folder XAMPP
```
Salin semua file ke: C:\xampp\htdocs\crs\
```

#### 3. Import Database
1. Buka browser, akses `http://localhost/phpmyadmin`
2. Klik tab **Import**
3. Pilih file `database/crs_database.sql`
4. Klik **Go** untuk mengimport

#### 4. Konfigurasi Database (Jika Diperlukan)
Edit file `includes/db_connect.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');      // Default XAMPP
define('DB_PASSWORD', '');            // Default XAMPP (kosong)
define('DB_NAME', 'crs_db');
```

#### 5. Jalankan Aplikasi
1. Buka XAMPP Control Panel
2. Start **Apache** dan **MySQL**
3. Buka browser: `http://localhost/crs/`

---

## 👤 Akun Demo

### 🔐 Admin Account
| Username | Password | Role |
|----------|----------|------|
| `admin` | `password` | Super Admin |
| `admin_hotel` | `password` | Admin Hotel |
| `admin_flight` | `password` | Admin Penerbangan |
| `admin_tour` | `password` | Admin Paket Wisata |
| `guru1` | `password` | Admin/Guru |

### 👥 Customer Account
| Username | Password | Nama |
|----------|----------|------|
| `customer1` | `password` | Ahmad Rizky |
| `customer2` | `password` | Siti Nurhaliza |
| `customer3` | `password` | Budi Pratama |
| `customer4` | `password` | Dewi Kusuma |
| `customer5` | `password` | Eko Wijaya |
| `siswa1` | `password` | Rina Amelia |
| `siswa2` | `password` | Fajar Hidayat |
| `siswa3` | `password` | Maya Sari |

> ⚠️ **Catatan**: Semua password menggunakan hash bcrypt. Password asli adalah `password`.

---

## 📁 Struktur Folder

```
computer-reservation-system/
├── database/
│   └── crs_database.sql          # Schema & dummy data
├── includes/
│   ├── db_connect.php            # Koneksi database
│   ├── header.php                # Header template
│   ├── footer.php                # Footer template
│   └── functions.php             # Helper functions
├── admin/                        # Panel Admin
│   ├── index.php                 # Dashboard
│   ├── login.php                 # Login admin
│   ├── hotels.php                # Kelola hotel
│   ├── flights.php               # Kelola penerbangan
│   ├── packages.php              # Kelola paket wisata
│   ├── bookings.php              # Semua pemesanan
│   └── customers.php             # Kelola customer
├── customer/                     # Panel Customer
│   ├── index.php                 # Dashboard customer
│   ├── login.php                 # Login customer
│   ├── register.php              # Registrasi
│   ├── book_hotel.php            # Booking hotel
│   ├── book_flight.php           # Booking penerbangan
│   ├── book_package.php          # Booking paket wisata
│   ├── my_bookings.php           # Riwayat pemesanan
│   └── profile.php               # Profil user
├── assets/
│   ├── css/style.css             # Custom styles
│   └── js/main.js                # JavaScript
└── index.php                     # Halaman utama
```

---

## 🗄️ Skema Database

### Tabel Utama
- `admins` - Data administrator
- `customers` - Data pelanggan
- `hotels` - Data hotel
- `hotel_rooms` - Tipe kamar per hotel
- `flights` - Data penerbangan
- `tour_packages` - Data paket wisata
- `hotel_bookings` - Pemesanan hotel
- `flight_bookings` - Pemesanan penerbangan
- `package_bookings` - Pemesanan paket wisata
- `payments` - Data pembayaran
- `reviews` - Ulasan/Review

### Views
- `v_hotel_booking_summary` - Ringkasan booking hotel
- `v_flight_booking_summary` - Ringkasan booking penerbangan
- `v_package_booking_summary` - Ringkasan booking paket wisata

---

## 🎓 Untuk Siswa SMK Pariwisata

### Materi Pembelajaran yang Tercakup
1. **Database Management**
   - Memahami relasi antar tabel (1:N, N:M)
   - Query SQL dasar hingga kompleks
   - Indexing dan optimization

2. **Web Development**
   - PHP dasar hingga OOP
   - Session management & authentication
   - Form handling & validation

3. **Frontend**
   - HTML5 & CSS3
   - Bootstrap 5 responsive design
   - JavaScript interaktif

4. **Sistem Reservasi**
   - Alur pemesanan hotel
   - Alur pemesanan penerbangan
   - Alur pemesanan paket wisata

---

## 🔧 Teknologi yang Digunakan

| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| PHP | 8.1+ | Backend processing |
| MySQL | 5.7+ | Database |
| Bootstrap | 5.3 | UI Framework |
| Bootstrap Icons | 1.10 | Icon library |
| Apache | 2.4+ | Web Server |

---

## 📸 Screenshot (Preview)

### Halaman Utama
- Hero section dengan pencarian
- Daftar hotel, penerbangan, dan paket wisata
- Card-based layout responsive

### Dashboard Admin
- Statistik real-time
- Manajemen data CRUD
- Tabel dengan pagination

### Dashboard Customer
- Riwayat pemesanan
- Form booking interaktif
- Profil pengguna

---

## 🤝 Cara Berkontribusi

1. Fork repository ini
2. Buat branch baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi **MIT** untuk tujuan edukasi.  
Dibuat dengan ❤️ untuk siswa SMK Pariwisata Indonesia.

---

## 📞 Kontak & Support

- 📧 Email: fauzan.nurahmadi@usu.ac.id


---

## 🙏 Ucapan Terima Kasih

Terima kasih kepada:
- Apache Friends untuk XAMPP
- Bootstrap Team untuk framework UI
- Semua guru dan siswa SMK Pariwisata

**Selamat Belajar dan Berkarya! 🎓✨**

---

> "Pendidikan adalah paspor untuk masa depan, karena hari esok adalah milik mereka yang mempersiapkannya hari ini." - Malcolm X
