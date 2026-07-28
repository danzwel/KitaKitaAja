# Analisis Modul Admin — Tahap 1

## Batasan modul

Modul ini hanya mencakup area Admin yang dikerjakan Daniel:

- login dan otorisasi Admin;
- dashboard statistik pengajuan dan intern aktif;
- kelola, detail, dan approval pengajuan;
- pembuatan akun intern saat pengajuan diterima;
- upload surat balasan;
- kelola intern magang;
- CRUD master bidang magang.

Data pengajuan berasal dari form pengajuan milik Sofi. Dashboard mahasiswa,
profil, absensi, dan download surat balasan milik Raihan hanya menggunakan data
yang disediakan modul ini dan tidak diubah di sini.

## Flow Admin

```text
Admin membuka /admin/login
        |
        v
Autentikasi guard admin + middleware admin.auth
        |
        v
Dashboard Admin
  |-- lihat statistik dan 5 pengajuan terbaru
  |-- kelola pengajuan -> detail -> proses approval
  |       |-- terima -> buat akun intern unik -> status diterima
  |       `-- tolak  -> wajib alasan -> status ditolak
  |-- kelola intern -> edit, detail, reset password, hapus
  |-- upload surat balasan PDF untuk intern diterima
  `-- master bidang -> tambah, ubah, aktif/nonaktif, hapus soft delete
```

## Use case

| Aktor | Use case | Hasil |
|---|---|---|
| Admin | Login/logout | Session guard `admin` aktif/nonaktif |
| Admin | Lihat dashboard | Statistik, grafik bulanan, pengajuan terbaru |
| Admin | Cari/filter/sort pengajuan | Daftar pengajuan terpaginasikan |
| Admin | Lihat detail dan dokumen | Data pengajuan dan path dokumen tampil |
| Admin | Terima pengajuan | Status `diterima`, satu akun intern dibuat |
| Admin | Tolak pengajuan | Status `ditolak`, alasan tersimpan |
| Admin | Upload surat balasan | File PDF tersimpan melalui Laravel Storage |
| Admin | Kelola intern | Cari, filter, edit, detail, hapus, reset password |
| Admin | Kelola bidang | CRUD bidang dengan status aktif dan soft delete |

## Status dan aturan bisnis

- Pengajuan baru berstatus `menunggu`.
- `menunggu` atau `diproses` dapat diproses satu kali.
- Approval menerima mengubah status menjadi `diterima` dan membuat satu intern
  aktif dengan username unik serta password ter-hash.
- Approval menolak wajib menyimpan `rejection_reason`.
- Surat balasan hanya boleh diunggah untuk intern yang berasal dari pengajuan
  diterima.
- Penghapusan department/intern/application menggunakan soft delete sesuai
  kebutuhan audit dan integritas data.

## ERD dan relasi

```text
admins 1 --------< applications
  |                    |
  |                    | 1
  |                    v
  |                interns 1 --------< reply_letters
  |                    ^                    ^
  `--------< reply_letters                 |
                       |                   |
departments 1 --------< applications       |
departments 1 --------< interns            |
```

Relasi Eloquent:

- `Department hasMany Application` dan `hasMany Intern`.
- `Application belongsTo Department`, `belongsTo Admin` melalui
  `processed_by`, dan `hasOne Intern`.
- `Intern belongsTo Application` dan `Department`, serta `hasMany ReplyLetter`.
- `ReplyLetter belongsTo Intern` dan `Admin` melalui `uploaded_by`.
- `Admin hasMany processedApplications` dan `uploadedReplyLetters`.

## Migration dan seeder

Migration yang menjadi fondasi modul:

1. `admins` — akun dan credential admin.
2. `departments` — master bidang, status aktif, soft delete.
3. `applications` — data pengajuan, dokumen, status, alasan penolakan, admin pemroses.
4. `interns` — akun mahasiswa hasil approval.
5. `reply_letters` — file surat balasan dan admin pengunggah.
6. index status/tanggal untuk query dashboard dan tabel.
7. unique `interns.application_id` untuk mencegah akun ganda dari satu pengajuan.

`AdminSeeder` memakai `ADMIN_NAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` dari
environment, dengan default development yang sudah tersedia. Sebelum production,
nilai password wajib diganti.

## Catatan koordinasi merge

Migration `applications` adalah kontrak dengan modul Form Pengajuan Sofi,
terutama nama kolom `cover_letter_path`, `cv_path`, dan `proposal_path`.
Nama kolom ini harus disepakati sebelum merge ke `develop` agar tidak membuat
dua migration atau mapping dokumen yang berbeda.

Model `ReplyLetter` belum tersedia di repository saat audit ini; implementasinya
ditahan untuk Tahap 2 sesuai alur kerja yang diminta.
