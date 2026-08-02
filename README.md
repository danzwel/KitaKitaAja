# SIM Magang — Sistem Informasi Manajemen Magang

Sistem Informasi Manajemen Magang untuk **UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat**.

Dibangun menggunakan framework **Laravel 12** dengan **Blade**, **Tailwind CSS**, dan **Vite**.

---

## 👥 Tim Pengembang

| Nama | Peran | Modul |
|------|-------|-------|
| **Sofi Aura Fatimah** | Frontend | Landing Page & Form Pendaftaran Mahasiswa |
| **Daniel Desmanto Nugraha** | Backend | Super Admin (Manajemen Akun, Kelola Pengajuan, Auto-Approval) |
| **Raihan Pratama** | Full-Stack | Login Mahasiswa, Dashboard Mahasiswa, Profil Mahasiswa |

---

## 📋 Fitur Utama

### 🌐 Landing Page (Sofi)
- Halaman utama dengan informasi magang
- Formulir pendaftaran / pengajuan magang online
- Cek status pengajuan dengan kode aplikasi
- Halaman persyaratan, FAQ, dan kontak

### 🔐 Super Admin (Daniel)
- Login admin terpisah dengan guard `admin`
- Dashboard admin dengan statistik dan grafik pengajuan
- Kelola pengajuan magang (verifikasi, terima, tolak)
- Manajemen mahasiswa magang aktif
- Manajemen bidang/departemen magang
- Auto-create akun mahasiswa saat pengajuan disetujui

### 🎓 Portal Mahasiswa (Raihan)
- **Login Mahasiswa** — Autentikasi menggunakan NIM/username & password
- **Force Change Password** — Wajib ganti password saat login pertama kali
- **Dashboard** — Menampilkan profil singkat, statistik absensi (dummy), dan menu cepat
- **Profil Mahasiswa** — Edit informasi kontak, upload foto profil, ganti password
- Middleware `EnsureInternHasChangedPassword` untuk keamanan
- Guard terpisah (`intern`) dari admin dan user default

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| Frontend | Blade Templates, Tailwind CSS |
| Build Tool | Vite |
| Database | SQLite (development) / MySQL (production) |
| Authentication | Laravel Breeze + Custom Guards |
| Icons | Tabler Icons (CDN) |
| Fonts | Inter, Plus Jakarta Sans, Poppins |

---

## ⚙️ Instalasi & Setup

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js >= 18 & npm

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/danzwel/KitaKitaAja.git
cd KitaKitaAja

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Jalankan migrasi & seeder
php artisan migrate --seed

# 7. Buat symlink storage (untuk upload foto)
php artisan storage:link

# 8. Jalankan development server
php artisan serve       # Terminal 1
npm run dev             # Terminal 2
```

Akses aplikasi di **http://localhost:8000**

---

## 🔑 Akun Default (Seeder)

### Admin
| Nama | Email | Password |
|------|-------|----------|
| Drg. Hj. Rini Kartikawati | `rini.kartikawati@uptdpelatihankesehatan.go.id` | `password123` |
| Asep Sutisna, S.Kep. | `asep.sutisna@uptdpelatihankesehatan.go.id` | `password123` |
| Dra. Nengsih Haryati | `nengsih.haryati@uptdpelatihankesehatan.go.id` | `password123` |

Akses: **http://localhost:8000/admin/login**

### Mahasiswa Magang
| Nama | NIM (Username) | Password | Bidang |
|------|----------------|----------|--------|
| Raihan Maulana Fadly | `2211102441` | `password123` | Teknologi Informasi |
| Sofia Risa Aulia | `2211102358` | `password123` | Teknologi Informasi |
| Daniel Desmanto Nugraha | `2211102390` | `password123` | Teknologi Informasi |
| Aisyah Nurhaliza | `2311101045` | `password123` | Rekam Medis |
| Muhammad Rizky Fauzan | `2111201078` | `password123` | Farmasi |
| Putri Wulandari | `2211303112` | `password123` | Keperawatan |
| Ahmad Fadilah | `2011104090` | `password123` | Kesehatan Masyarakat |

Akses: **http://localhost:8000/mahasiswa/login**

> ⚠️ Semua akun mahasiswa akan diminta **wajib ganti password** saat login pertama kali.

---

## 📁 Struktur Proyek (Modul Mahasiswa)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Intern/
│   │       ├── Auth/
│   │       │   └── AuthenticatedSessionController.php  # Login & Logout
│   │       ├── DashboardController.php                 # Dashboard
│   │       ├── ForceChangePasswordController.php       # Ganti password pertama
│   │       └── ProfileController.php                   # Edit profil & password
│   └── Middleware/
│       └── EnsureInternHasChangedPassword.php          # Cek password sementara
├── Models/
│   └── Intern.php                                      # Model mahasiswa magang
│
resources/views/
├── components/
│   └── intern/layouts/
│       └── app.blade.php                               # Layout dashboard mahasiswa
├── intern/
│   ├── auth/
│   │   ├── login.blade.php                             # Halaman login
│   │   └── force-change-password.blade.php             # Halaman ganti password
│   ├── dashboard.blade.php                             # Dashboard mahasiswa
│   └── profile/
│       └── edit.blade.php                              # Halaman edit profil
│
routes/
└── web.php                                             # Route mahasiswa (prefix /mahasiswa)
```

---

## 🔒 Alur Autentikasi Mahasiswa

```
Admin menyetujui pengajuan
        │
        ▼
Akun intern otomatis dibuat (username = NIM, password acak)
        │
        ▼
Mahasiswa login di /mahasiswa/login
        │
        ▼
Middleware cek: temporary_initial_password masih ada?
        │
   ┌────┴────┐
   Ya       Tidak
   │         │
   ▼         ▼
Redirect   Akses
ke ganti   Dashboard
password   & Profil
```

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik.

© 2026 UPTD Pelatihan Kesehatan Dinas Kesehatan Provinsi Jawa Barat
