# PANDUAN LENGKAP INSTALASI & GITHUB

## Daftar Isi
1. Persyaratan Sistem
2. Instalasi XAMPP
3. Import Database
4. Menjalankan Aplikasi
5. Upload ke GitHub
6. Troubleshooting

---

## Persyaratan Sistem

### Minimum Requirements
- OS: Windows 10/11, macOS, atau Linux
- RAM: 4 GB (disarankan 8 GB)
- Storage: 2 GB ruang kosong
- Browser: Chrome, Firefox, Edge (versi terbaru)

### Software yang Diperlukan
1. XAMPP (PHP 8.1+, MySQL, Apache)
   - Download: https://www.apachefriends.org
   - Pilih versi dengan PHP 8.1 atau lebih tinggi

2. Git (opsional, untuk upload ke GitHub)
   - Download: https://git-scm.com/downloads

3. Code Editor (opsional)
   - VS Code: https://code.visualstudio.com
   - Notepad++: https://notepad-plus-plus.org

---

## Instalasi XAMPP

### Windows

1. Download XAMPP
   - Kunjungi: https://www.apachefriends.org/download.html
   - Pilih: XAMPP for Windows (PHP 8.1.x)

2. Install XAMPP
   a. Klik 2x file installer xampp-windows-x64-8.1.x-x-x-installer.exe
   b. Klik "Yes" jika muncul User Account Control (UAC)
   c. Klik "Next" pada Setup Wizard
   d. Pilih komponen yang diinstall (minimal Apache, MySQL, PHP, phpMyAdmin)
   e. Pilih folder instalasi (default: C:\xampp)
   f. Klik "Next" sampai selesai
   g. Klik "Finish"

3. Konfigurasi XAMPP
   a. Buka XAMPP Control Panel dari Start Menu
   b. Klik "Config" pada Apache
   c. Pilih "PHP (php.ini)"
   d. Cari dan ubah beberapa setting:

      upload_max_filesize = 10M
      post_max_size = 10M
      max_execution_time = 300
      memory_limit = 256M

   e. Save dan tutup file

### macOS

1. Download XAMPP untuk macOS
   - Pilih versi macOS dari website Apache Friends

2. Install
   a. Mount file DMG
   b. Drag XAMPP folder ke Applications
   c. Buka XAMPP dari Applications/XAMPP/manager-osx.app

### Linux

1. Download dan Install
   ```bash
   cd /tmp
   wget https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.1.x/xampp-linux-x64-8.1.x-x-installer.run
   chmod +x xampp-linux-x64-8.1.x-x-installer.run
   sudo ./xampp-linux-x64-8.1.x-x-installer.run
   ```

2. Jalankan XAMPP
   ```bash
   sudo /opt/lampp/lampp start
   ```

---

## Import Database

### Metode 1: Via phpMyAdmin (Direkomendasikan)

1. Start Apache dan MySQL
   a. Buka XAMPP Control Panel
   b. Klik "Start" pada Apache
   c. Klik "Start" pada MySQL
   d. Pastikan kedua service berjalan (warna hijau)

2. Buka phpMyAdmin
   - Buka browser, ketik: http://localhost/phpmyadmin

3. Import Database
   a. Klik tab "Import" di menu atas
   b. Klik "Choose File" atau "Browse"
   c. Pilih file: crs_database.sql
   d. Pastikan format dipilih: SQL
   e. Klik "Go" atau "Import"
   f. Tunggu sampai muncul pesan sukses

### Metode 2: Via Command Line

1. Buka Terminal/Command Prompt
   ```bash
   # Windows
   cd C:\xampp\mysql\bin

   # macOS/Linux
   cd /Applications/XAMPP/xamppfiles/bin
   # atau
   cd /opt/lampp/bin
   ```

2. Login ke MySQL
   ```bash
   mysql -u root -p
   # Password default XAMPP biasanya kosong, tekan Enter saja
   ```

3. Import Database
   ```sql
   CREATE DATABASE IF NOT EXISTS crs_db;
   USE crs_db;
   SOURCE /path/to/crs_database.sql;
   ```

### Verifikasi Import

```sql
-- Cek tabel yang berhasil dibuat
SHOW TABLES;

-- Cek data admin
SELECT * FROM admins;

-- Cek data customer
SELECT * FROM customers;

-- Cek data hotel
SELECT * FROM hotels;
```

---

## Menjalankan Aplikasi

### 1. Pindahkan File Project

**Windows:**
```
Copy folder crs ke: C:\xampp\htdocs\
Hasil: C:\xampp\htdocs\crs\
```

**macOS:**
```bash
cp -r crs /Applications/XAMPP/htdocs/
```

**Linux:**
```bash
sudo cp -r crs /opt/lampp/htdocs/
```

### 2. Verifikasi Struktur Folder

```
C:\xampp\htdocs\crs\ (atau /opt/lampp/htdocs/crs/)
├── index.php
├── .htaccess
├── README.md
├── admin/
│   ├── index.php
│   ├── login.php
│   └── ...
├── customer/
│   ├── index.php
│   ├── login.php
│   └── ...
├── includes/
│   ├── db_connect.php
│   ├── header.php
│   └── ...
└── assets/
    ├── css/
    ├── js/
    └── images/
```

### 3. Akses Aplikasi

Buka browser dan ketik:

- Halaman Utama:     http://localhost/crs/
- Admin Login:       http://localhost/crs/admin/login.php
- Customer Login:    http://localhost/crs/customer/login.php
- phpMyAdmin:        http://localhost/phpmyadmin

### 4. Login Pertama Kali

**Admin:**
- Username: admin
- Password: password

**Customer:**
- Username: customer1
- Password: password

---

## Upload ke GitHub

### Metode 1: Menggunakan Git Command Line

#### Step 1: Install Git

**Windows:**
- Download dari: https://git-scm.com/download/win
- Install dengan default settings

**macOS:**
```bash
# Install Homebrew dulu jika belum
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install Git
brew install git
```

**Linux:**
```bash
sudo apt-get update
sudo apt-get install git
```

#### Step 2: Konfigurasi Git

```bash
git config --global user.name "Nama Anda"
git config --global user.email "email@example.com"
```

#### Step 3: Inisialisasi Repository

```bash
# Masuk ke folder project
cd C:\xampp\htdocs\crs

# Inisialisasi Git
git init

# Tambahkan semua file
git add .

# Commit pertama
git commit -m "Initial commit: CRS SMK Pariwisata v1.0"
```

#### Step 4: Buat Repository di GitHub

1. Buka https://github.com/new
2. Isi Repository name: computer-reservation-system
3. Isi Description: Sistem Reservasi Komputer untuk SMK Pariwisata
4. Pilih Public (agar semua bisa akses)
5. JANGAN centang "Add a README file" (sudah ada)
6. Klik Create repository

#### Step 5: Push ke GitHub

```bash
# Tambahkan remote repository
git remote add origin https://github.com/username-anda/computer-reservation-system.git

# Push ke branch main
git branch -M main
git push -u origin main
```

#### Step 6: Verifikasi

Buka: https://github.com/username-anda/computer-reservation-system
Pastikan semua file muncul di repository

---

### Metode 2: Upload Manual via Browser

1. Buat Repository di GitHub
   a. Login ke GitHub
   b. Klik tombol "+" di pojok kanan atas
   c. Pilih "New repository"
   d. Isi nama dan deskripsi
   e. Klik "Create repository"

2. Upload File
   a. Di halaman repository baru, klik "uploading an existing file"
   b. Klik "choose your files"
   c. Pilih semua file dan folder project
   d. Tunggu upload selesai
   e. Isi commit message: "Initial commit"
   f. Klik "Commit changes"

---

### Metode 3: Menggunakan GitHub Desktop

1. Download GitHub Desktop
   - https://desktop.github.com

2. Login dan Setup
   a. Install dan buka GitHub Desktop
   b. Login dengan akun GitHub
   c. Pilih "Add an Existing Repository from your Hard Drive"
   d. Pilih folder: C:\xampp\htdocs\crs

3. Publish Repository
   a. Isi nama repository
   b. Pilih Public
   c. Klik Publish repository

---

## Konfigurasi Setelah Upload

### Tambahkan Topics/Tags

Buka repository di GitHub
Klik ikon roda gigi (settings) di sebelah About
Tambahkan topics:
- php
- mysql
- xampp
- reservation-system
- hotel-booking
- flight-booking
- tourism
- smk
- education
Klik Save changes

### Aktifkan GitHub Pages (Opsional)

Settings - Pages - Source - Deploy from a branch - main - / (root)
Tunggu beberapa menit, lalu akses: https://username-anda.github.io/computer-reservation-system

---

## Troubleshooting

### Masalah Umum

#### 1. Apache Tidak Bisa Start

Solusi:
a. Cek apakah port 80/443 sudah digunakan
   Buka CMD: netstat -ano | findstr :80

b. Jika port 80 dipakai Skype/VMware:
   - Ganti port Apache di XAMPP Control Panel
   - Config - Apache (httpd.conf)
   - Cari "Listen 80" ganti jadi "Listen 8080"
   - Cari "ServerName localhost:80" ganti jadi "ServerName localhost:8080"

c. Jalankan sebagai Administrator
   - Klik kanan XAMPP Control Panel - Run as administrator

#### 2. MySQL Tidak Bisa Start

Solusi:
a. Cek apakah port 3306 sudah digunakan
   Buka CMD: netstat -ano | findstr :3306

b. Hapus file lock:
   - Buka folder: C:\xampp\mysql\data
   - Hapus file: ibdata1, ib_logfile0, ib_logfile1
   - Restart MySQL

c. Cek error log:
   - Buka: C:\xampp\mysql\data\mysql_error.log

#### 3. Database Connection Error

Solusi:
a. Cek koneksi di includes/db_connect.php
   - Pastikan DB_HOST = 'localhost'
   - Pastikan DB_USERNAME = 'root'
   - Pastikan DB_PASSWORD = '' (kosong untuk XAMPP default)

b. Cek apakah database sudah dibuat:
   - Buka phpMyAdmin
   - Cek apakah database 'crs_db' ada

c. Cek privileges user:
   - phpMyAdmin - User accounts
   - Pastikan 'root'@'localhost' punya ALL PRIVILEGES

#### 4. Halaman Blank/Error 500

Solusi:
a. Enable error reporting di PHP:
   - Buka php.ini
   - Cari: display_errors = Off
   - Ganti jadi: display_errors = On
   - Restart Apache

b. Cek error log Apache:
   - Buka: C:\xampp\apache\logs\error.log

c. Pastikan semua file PHP tidak corrupt

#### 5. CSS/JS Tidak Load

Solusi:
a. Cek path di header.php:
   - Pastikan base_path sudah benar

b. Clear browser cache:
   - Ctrl + Shift + R (hard refresh)

c. Cek file .htaccess:
   - Pastikan tidak ada rewrite yang block assets

#### 6. Session Tidak Berfungsi

Solusi:
a. Cek folder session PHP:
   - Buka php.ini
   - Cari: session.save_path
   - Pastikan folder ada dan writable

b. Start session di setiap file:
   - Pastikan session_start() di paling atas file
   - Sebelum output apapun (termasuk spasi)

---

## Tips dan Trik

### Backup Database

```bash
# Via Command Line
cd C:\xampp\mysql\bin
mysqldump -u root -p crs_db > backup_crs_$(date +%Y%m%d).sql
```

### Update Project

```bash
# Setelah ada perubahan
git add .
git commit -m "Update: deskripsi perubahan"
git push origin main
```

### Clone Repository (Untuk Siswa Lain)

```bash
git clone https://github.com/username-anda/computer-reservation-system.git
cd computer-reservation-system
# Copy ke htdocs dan import database
```

---

## Support

Jika mengalami masalah:
1. Cek error log di folder logs XAMPP
2. Google error message yang muncul
3. Tanya di forum: Stack Overflow, GitHub Discussions
4. Hubungi guru/teknisi sekolah

---

## Lisensi

Proyek ini dirilis di bawah MIT License untuk tujuan edukasi.

Selamat Belajar dan Berkarya!
