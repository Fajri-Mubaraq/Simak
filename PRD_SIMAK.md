# PRODUCT REQUIREMENT DOCUMENT (PRD)
## SIMAK — Sistem Informasi Manajemen Akademik

---

### 1. PROJECT OVERVIEW

**Nama Aplikasi:** SIMAK (Sistem Informasi Manajemen Akademik)
**Versi:** 1.0.0
**Tanggal:** Juli 2026
**Framework:** Laravel 11 (PHP 8.x)
**Database:** MySQL 8.x

#### Latar Belakang Masalah
Pengelolaan data akademik di banyak institusi pendidikan masih dilakukan secara manual atau menggunakan spreadsheet yang tidak terintegrasi. Hal ini menyebabkan:
- Kesulitan dalam pencarian dan pembaruan data mahasiswa
- Tidak adanya sentralisasi data nilai dan mata kuliah
- Risiko kehilangan atau duplikasi data yang tinggi
- Proses perhitungan IPK yang memakan waktu dan rawan error

#### Solusi
SIMAK hadir sebagai aplikasi web manajemen akademik yang terpusat, aman, dan mudah digunakan oleh administrator/staf akademik untuk mengelola data mahasiswa, mata kuliah, dan nilai secara efisien.

#### Target Pengguna
Admin kampus / staf akademik yang mengelola data mahasiswa dan nilai.

---

### 2. USER PERSONAS & USER FLOW

#### User Persona
| Atribut | Detail |
|---------|--------|
| **Nama** | Budi Santoso |
| **Peran** | Staf Administrasi Akademik |
| **Kebutuhan** | Mengelola data mahasiswa, input nilai, laporan akademik |
| **Tantangan** | Data tersebar, proses manual lambat |

#### User Flow

```
[Pengguna Buka URL]
    │
    ▼
[Halaman Login]
    │─── (input email + password) ──►
    │
    ▼
[Validasi Kredensial]
    │─── Gagal ──► [Tampil Error, Kembali ke Login]
    │─── Berhasil ──►
    │
    ▼
[Dashboard] ──── Lihat statistik & data terbaru
    │
    ├──► [Menu: Data Mahasiswa]
    │       ├── Index (Daftar + Search + Filter)
    │       ├── Tambah Mahasiswa (Form + Validasi)
    │       ├── Lihat Detail (Profil + Transkrip Nilai)
    │       ├── Edit Data Mahasiswa
    │       └── Hapus Mahasiswa (Konfirmasi)
    │
    ├──► [Menu: Mata Kuliah]
    │       ├── Index (Daftar + Filter Jurusan/Semester)
    │       ├── Tambah Mata Kuliah
    │       ├── Detail (Info + Daftar Mahasiswa yg Mengambil)
    │       ├── Edit Mata Kuliah
    │       └── Hapus Mata Kuliah
    │
    ├──► [Menu: Data Nilai]
    │       ├── Index (Rekap + Filter)
    │       ├── Input Nilai (Kalkulasi Otomatis)
    │       ├── Detail Nilai
    │       ├── Edit Nilai
    │       └── Hapus Nilai
    │
    └──► [Logout] ──► [Kembali ke Login]
```

---

### 3. FUNCTIONAL REQUIREMENTS

| ID | Nama Fitur | Deskripsi Perilaku | Status |
|----|-----------|-------------------|--------|
| F01 | Autentikasi Login | Admin login menggunakan email & password yang terverifikasi ke database | Wajib |
| F02 | Logout | Sesi admin dihapus, diarahkan ke halaman login | Wajib |
| F03 | Proteksi Route | Semua halaman selain login hanya bisa diakses setelah login (middleware) | Wajib |
| F04 | Dashboard | Menampilkan total mahasiswa, mata kuliah, nilai, dan rata-rata nilai; grafik distribusi jurusan | Wajib |
| F05 | CRUD Mahasiswa | Tambah, lihat, edit, hapus data mahasiswa dengan validasi NIM unik | Wajib |
| F06 | Search Mahasiswa | Pencarian mahasiswa berdasarkan NIM, nama, atau email | Wajib |
| F07 | Filter Mahasiswa | Filter berdasarkan jurusan, status, dan angkatan | Wajib |
| F08 | Pagination | Data ditampilkan 10 per halaman | Wajib |
| F09 | CRUD Mata Kuliah | Tambah, lihat, edit, hapus data mata kuliah | Wajib |
| F10 | CRUD Nilai | Input, lihat, edit, hapus data nilai mahasiswa | Wajib |
| F11 | Auto-Kalkulasi Nilai | Nilai huruf dan bobot dihitung otomatis dari nilai angka (0-100) | Wajib |
| F12 | Preview Nilai Real-time | Nilai huruf muncul secara live saat input nilai angka (JavaScript) | Opsional |
| F13 | Transkrip Mahasiswa | Halaman detail mahasiswa menampilkan semua nilai beserta IPK | Wajib |
| F14 | Flash Messages | Notifikasi sukses/error muncul setelah operasi CRUD | Wajib |

---

### 4. NON-FUNCTIONAL REQUIREMENTS

#### Stack Teknologi
| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11, PHP 8.2+ |
| Frontend | Bootstrap 5.3, Vanilla CSS, JavaScript ES6 |
| Database | MySQL 8.x |
| Server Lokal | Laragon (Apache/Nginx + PHP + MySQL) |
| Font | Google Fonts - Inter |
| Icons | Font Awesome 6.5 |
| Session | File-based Session |
| Autentikasi | Custom Session Middleware |

#### Keamanan (Security)
- Password di-hash menggunakan **bcrypt** (`Hash::make()`)
- Semua form menggunakan token **CSRF** (`@csrf`)
- Input divalidasi di sisi server (Laravel `Request::validate()`)
- Akses route dilindungi **AuthMiddleware** (redirect ke login jika belum auth)
- SQL Injection dicegah menggunakan **Eloquent ORM** (parameterized queries)
- Konfirmasi JavaScript sebelum operasi **hapus** data

#### Validasi Input
| Field | Aturan Validasi |
|-------|----------------|
| NIM | Wajib, unik, maks 20 karakter |
| Nama | Wajib, maks 100 karakter |
| Email | Format email, unik (nullable) |
| Nilai Angka | Angka, 0–100 |
| SKS | Integer, 1–6 |
| Semester | Integer, 1–8 |
| Angkatan | Integer, 2000–tahun ini |

---

### 5. DATABASE SCHEMA (ERD)

#### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK) | Auto increment |
| name | VARCHAR(255) | Nama admin |
| email | VARCHAR(255) | Email (unique) |
| password | VARCHAR(255) | Password bcrypt |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `mahasiswas`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK) | |
| nim | VARCHAR(20) | Unique |
| nama | VARCHAR(100) | |
| jenis_kelamin | ENUM('L','P') | |
| jurusan | VARCHAR(100) | |
| program_studi | VARCHAR(100) | |
| angkatan | YEAR | |
| email | VARCHAR(100) | Nullable, unique |
| no_telp | VARCHAR(20) | Nullable |
| alamat | TEXT | Nullable |
| status | ENUM('Aktif','Cuti','Lulus','DO') | Default: Aktif |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `mata_kuliahs`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK) | |
| kode_mk | VARCHAR(20) | Unique |
| nama_mk | VARCHAR(150) | |
| sks | TINYINT | 1–6 |
| semester | TINYINT | 1–8 |
| jurusan | VARCHAR(100) | |
| dosen | VARCHAR(100) | Nullable |
| status | ENUM('Aktif','Nonaktif') | Default: Aktif |
| deskripsi | TEXT | Nullable |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Tabel `nilais`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT (PK) | |
| mahasiswa_id | BIGINT (FK) | → mahasiswas.id |
| mata_kuliah_id | BIGINT (FK) | → mata_kuliahs.id |
| semester_ambil | VARCHAR(20) | e.g. "2024/2025 Ganjil" |
| nilai_angka | DECIMAL(5,2) | 0.00–100.00 |
| nilai_huruf | VARCHAR(2) | A, A-, B+, ... |
| bobot | DECIMAL(3,2) | 0.00–4.00 |
| status | ENUM | Lulus/Tidak Lulus/Mengulang |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### Relasi
```
users (1) ─── tidak ada relasi langsung ke mahasiswas (admin sistem)
mahasiswas (1) ──── (N) nilais
mata_kuliahs (1) ── (N) nilais
```

---

### 6. SISTEM KONVERSI NILAI

| Rentang Angka | Nilai Huruf | Bobot |
|:---:|:---:|:---:|
| ≥ 85 | A | 4.00 |
| 80 – 84 | A- | 3.75 |
| 75 – 79 | B+ | 3.50 |
| 70 – 74 | B | 3.00 |
| 65 – 69 | B- | 2.75 |
| 60 – 64 | C+ | 2.50 |
| 55 – 59 | C | 2.00 |
| 50 – 54 | D | 1.00 |
| < 50 | E | 0.00 |

---

### 7. CARA MENJALANKAN APLIKASI

**Prasyarat:**
- Laragon (PHP 8.x, MySQL 8.x)
- Composer
- Browser modern

**Langkah:**
1. Nyalakan Laragon (Start All)
2. Double-click file `setup.bat` di folder proyek
3. Tunggu proses selesai (migrate + seed)
4. Jalankan: `php artisan serve`
5. Buka browser: `http://localhost:8000`
6. Login: `admin@simak.ac.id` / `admin123`

---

### 8. AI USAGE REFLECTION

Proyek ini dikembangkan dengan bantuan AI **Antigravity (Google DeepMind)** sebagai coding assistant untuk:
- Mempercepat penulisan boilerplate code (migration, model, controller)
- Memvalidasi logika bisnis (konversi nilai, relasi database)
- Debugging error routing dan middleware
- Menghasilkan seed data yang realistis

Pengembang bertanggung jawab penuh atas seluruh arsitektur, desain database, logika aplikasi, dan validasi setiap komponen kode yang dihasilkan.

---

*Dokumen PRD ini dibuat sebagai bagian dari UAS Pemrograman Web 2.*
