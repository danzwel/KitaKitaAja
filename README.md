# SIM Magang UPTD Pelatihan Kesehatan

Sistem Informasi Manajemen Magang untuk **UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat**. Aplikasi ini menangani pengajuan magang, penerimaan dan penempatan peserta, QR absensi, rekap jam kerja, laporan akhir, serta pengelolaan akun oleh admin.

> Repository ini tidak menyertakan data peserta, dokumen, foto, maupun data operasional. Instalasi baru dimulai dalam keadaan bersih.

## Fitur

### Portal publik

- Pengajuan magang untuk mahasiswa, siswa SMK/SMA sederajat, atau peserta lain.
- Unggah foto, transkrip nilai, surat pengantar, dan CV sesuai kebutuhan.
- Cek dan unduh status pengajuan menggunakan nomor pengajuan.
- Informasi persyaratan, FAQ, kontak email, telepon, dan Instagram UPTD.

### Admin

- Meninjau, menerima, atau menolak pengajuan serta menentukan bidang magang peserta.
- Mengelola peserta, bidang magang, akun admin, surat balasan, dan laporan hasil magang.
- Membuat QR absensi datang/pulang dengan validasi lokasi GPS.
- Memproses izin, sakit, dan izin tetap hadir untuk peserta yang memiliki kelas atau keperluan lain.
- Konfirmasi kehadiran manual untuk kasus lupa scan datang, pulang, atau keduanya.
- Rekap absensi per peserta, jam kerja tercatat, dan unduhan PDF/Excel.
- Melihat riwayat scan pada tanggal tertentu serta mengunduh PDF/Excel harian.
- Melihat dokumentasi yang diunggah mahasiswa setelah masa magangnya selesai.

### Portal peserta

- Login terpisah dan wajib mengganti kata sandi awal.
- Scan QR kehadiran, riwayat absensi, jam kerja, izin/sakit, dan izin tetap hadir.
- Unggah laporan hasil magang yang dapat dilihat admin.
- Setelah masa magang selesai, unggah dokumentasi kegiatan hingga 5 foto yang dapat dilihat admin.
- Setelah periode magang selesai, akun otomatis masuk mode **Magang Selesai**: absensi dan izin/sakit dinonaktifkan, sedangkan rekap, laporan, dan profil tetap dapat diakses.

## Teknologi

| Komponen | Teknologi |
| --- | --- |
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Tailwind CSS, Vite |
| Database | MySQL 8+ / MariaDB |
| Spreadsheet | PhpSpreadsheet |
| PDF | Dompdf |
| Ikon | Tabler Icons |

## Instalasi lokal

### Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- Node.js 18 atau lebih baru dan npm
- MySQL 8+ atau MariaDB

### Langkah

```bash
git clone https://github.com/danzwel/KitaKitaAja.git
cd KitaKitaAja

composer install
npm install

# Linux/macOS
cp .env.example .env

# Windows PowerShell
# Copy-Item .env.example .env

php artisan key:generate
```

Buat database MySQL, lalu sesuaikan koneksinya pada `.env`.

```sql
CREATE DATABASE kitakitaaja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
php artisan migrate --seed
php artisan storage:link

# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Aplikasi tersedia pada [http://localhost:8000](http://localhost:8000).

Untuk membuat aset produksi, jalankan:

```bash
npm run build
```

## Deployment tanpa data peserta dummy

Jika database lokal akan di-export ke hosting, jalankan perintah berikut **sekali di hosting** setelah migrasi atau import database:

```bash
php artisan app:purge-participant-data
```

Perintah ini menghapus pengajuan, akun peserta, absensi, izin, surat, dan file upload peserta. Akun admin serta data bidang magang (`bidangs`/`departments`) tetap dipertahankan. Gunakan `--force` hanya setelah memastikan database yang aktif adalah database hosting:

```bash
php artisan app:purge-participant-data --force
```

Untuk instalasi database baru, `InternSeeder` memang kosong sehingga akun peserta dummy tidak dibuat. Jangan menjalankan perintah purge pada database lokal jika masih ingin mempertahankan data pengujian.

## Akun awal instalasi baru

Seeder hanya membuat **satu akun admin** dan tidak membuat peserta, bidang, pengajuan, absensi, atau dokumen contoh.

| Peran | Email | Kata sandi awal |
| --- | --- | --- |
| Administrator UPTD Pelatihan Kesehatan | `admin@upelkes.local` | `password` |

Masuk melalui [http://localhost:8000/admin/login](http://localhost:8000/admin/login), lalu segera ganti kata sandi dan buat bidang magang melalui menu admin.

## Penjadwalan status magang

Status peserta otomatis berubah menjadi **Selesai** sehari setelah tanggal akhir magang. Pada server produksi, jalankan Laravel scheduler setiap menit:

```bash
* * * * * cd /path/to/KitaKitaAja && php artisan schedule:run >> /dev/null 2>&1
```

Untuk pengembangan lokal, jalankan:

```bash
php artisan schedule:work
```

## Pengujian

```bash
php artisan test
```

## Keamanan data

- Jangan commit `.env`, database lokal, atau folder upload `storage`.
- Berkas peserta dan data operasional tersimpan pada database/storage lokal atau server, bukan di repository.
- Ubah kata sandi admin awal sebelum aplikasi digunakan.

## Tim Pengembang

| Nama | Peran |
| --- | --- |
| Sofia Risa Aulia | Frontend |
| Daniel Desmanto Nugraha | Full-stack |
| Raihan Maulana Fadly | Backend|

## Lisensi

Proyek ini dibuat untuk keperluan akademik.

© 2026 UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat
