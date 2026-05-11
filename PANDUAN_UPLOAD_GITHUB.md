# 📤 PANDUAN UPLOAD MANUAL KE GITHUB

## Cara Upload File ZIP ke GitHub (Tanpa Git Command Line)

### Langkah 1: Persiapan File

1. **Ekstrak File ZIP**
   ```
   a. Download file: computer-reservation-system.zip
   b. Klik kanan file ZIP → "Extract All" atau "Extract Here"
   c. Pilih folder tujuan (misal: Desktop)
   d. Klik "Extract"
   ```

2. **Verifikasi Hasil Ekstrak**
   ```
   Pastikan struktur folder seperti ini:
   computer-reservation-system/
   ├── index.php
   ├── README.md
   ├── admin/
   ├── customer/
   ├── includes/
   └── assets/
   ```

---

### Langkah 2: Buat Repository di GitHub

1. **Login ke GitHub**
   - Buka: https://github.com/login
   - Masukkan username dan password

2. **Buat Repository Baru**
   ```
   a. Klik tombol "+" di pojok kanan atas
   b. Pilih "New repository"

   atau langsung ke:
   https://github.com/new
   ```

3. **Isi Informasi Repository**
   ```
   Repository name: computer-reservation-system
   Description: Sistem Reservasi Komputer untuk SMK Pariwisata

   ☑️ Pilih "Public" (agar semua bisa akses gratis)
   ☐ JANGAN centang "Add a README file" (sudah ada di file)
   ☐ JANGAN centang "Add .gitignore" (sudah ada di file)
   ☐ JANGAN centang "Choose a license"

   Klik: "Create repository"
   ```

---

### Langkah 3: Upload File ke GitHub

#### Metode A: Drag & Drop (Paling Mudah)

```
1. Di halaman repository baru, klik:
   "uploading an existing file" 
   (atau klik "Add file" → "Upload files")

2. Buka folder hasil ekstrak ZIP di File Explorer

3. Pilih SEMUA file dan folder:
   - index.php
   - README.md
   - .htaccess
   - .gitignore
   - admin/ (folder)
   - customer/ (folder)
   - includes/ (folder)
   - assets/ (folder)
   - database/ (folder)

4. DRAG & DROP ke area upload di browser GitHub

5. Tunggu sampai semua file ter-upload (ada progress bar)
```

#### Metode B: Pilih File Manual

```
1. Klik "choose your files"

2. Di file picker, navigasi ke folder hasil ekstrak

3. Pilih semua file (Ctrl+A untuk select all)

4. Klik "Open"

5. Tunggu upload selesai
```

---

### Langkah 4: Commit Changes

```
1. Scroll ke bawah ke bagian "Commit changes"

2. Isi Commit message:
   "Initial commit - CRS SMK Pariwisata v1.0"

   (atau biarkan default "Add files via upload")

3. Pastikan pilih:
   ☑️ "Commit directly to the main branch"

4. Klik tombol hijau:
   "Commit changes"
```

---

### Langkah 5: Verifikasi Upload

```
1. Tunggu halaman reload

2. Pastikan semua file muncul di repository:
   - Cek tab "Code"
   - Lihat daftar file
   - Pastikan tidak ada yang missing

3. Klik README.md untuk cek tampilan

4. Cek struktur folder:
   - Klik folder admin/
   - Klik folder customer/
   - Pastikan isinya lengkap
```

---

### Langkah 6: Tambahkan Topics (Opsional tapi Direkomendasikan)

```
1. Di halaman repository, klik ikon roda gigi (⚙️) 
   di sebelah kanan "About"

2. Di bagian "Topics", ketik dan tambahkan:
   - php
   - mysql
   - xampp
   - reservation-system
   - hotel-booking
   - flight-booking
   - tourism
   - smk
   - education
   - indonesia

3. Klik "Save changes"
```

---

### Langkah 7: Bagikan Link ke Siswa

```
1. Copy URL repository:
   https://github.com/username-anda/computer-reservation-system

2. Bagikan via:
   - WhatsApp Group
   - Google Classroom
   - Email
   - Website sekolah

3. Siswa bisa:
   - Klik "Code" → "Download ZIP" untuk download
   - Atau clone dengan Git
```

---

## Tips & Troubleshooting

### File Tidak Bisa Upload?

```
✓ Cek ukuran file:
  - Maksimal per file: 25 MB (via browser)
  - Jika lebih besar, gunakan Git command line

✓ Cek format file:
  - GitHub support semua format
  - Pastikan file tidak corrupt

✓ Cek browser:
  - Gunakan Chrome/Firefox/Edge terbaru
  - Clear cache jika bermasalah
  - Disable adblocker sementara
```

### Folder Tidak Muncul?

```
✓ Pastikan upload folder, bukan hanya file
✓ GitHub akan otomatis buat struktur folder
✓ Refresh halaman (F5) jika tidak muncul
```

### README Tidak Tampil?

```
✓ Pastikan nama file: README.md (huruf besar)
✓ Pastikan ada di root folder
✓ Klik file untuk preview
```

---

## Alternatif: Upload via GitHub Desktop

```
1. Download GitHub Desktop:
   https://desktop.github.com

2. Install dan login

3. Klik "File" → "Add local repository"

4. Pilih folder hasil ekstrak ZIP

5. Klik "Publish repository"

6. Isi nama dan pilih "Public"

7. Klik "Publish"
```

---

## Catatan Penting

⚠️ **JANGAN upload file ZIP langsung ke GitHub!**
   - GitHub bukan tempat penyimpanan file ZIP
   - Upload file dan folder hasil ekstrak
   - Agar siswa bisa lihat source code langsung

✅ **Keuntungan Upload Extracted Files:**
   - Siswa bisa baca source code online
   - Bisa clone dengan Git
   - Bisa download ZIP dari GitHub
   - Version control berfungsi

---

## Video Tutorial Referensi

- How To Upload A Zip File To GitHub: https://www.youtube.com/watch?v=zqPw1c8MPP4
- How to Upload Zip on Github 2026: https://www.youtube.com/watch?v=5otO5G1GaCU
- Uploading a project to GitHub: https://docs.github.com/en/get-started/start-your-journey/uploading-a-project-to-github

---

## Kontak Support

Jika mengalami masalah upload:
1. Cek dokumentasi GitHub: https://docs.github.com
2. Tanya di GitHub Community
3. Hubungi guru/teknisi sekolah

Selamat Berbagi Ilmu! 🎓✨
