# 📦 Sistem Manajemen Barang Gudang Kantor

Sistem Manajemen Barang adalah aplikasi web berbasis Laravel 11 yang dirancang untuk mengelola stok barang dan memproses permintaan barang di lingkungan kantor atau gudang. Aplikasi ini memisahkan peran pengguna menjadi dua kategori: **Karyawan** (sebagai peminta barang) dan **Operator** (sebagai pengelola gudang).

---

## 📋 Daftar Isi

-   [Deskripsi Project](#-deskripsi-project)
-   [Fitur Utama](#-fitur-utama)
-   [Tech Stack](#-tech-stack)
-   [Schema Database](#-schema-database)
-   [Instalasi](#-instalasi)
-   [Penggunaan](#-penggunaan)
-   [Struktur Project](#-struktur-project)
-   [License](#-license)

---

## 📝 Deskripsi Project

Sistem Manajemen Barang Gudang Kantor adalah solusi terintegrasi untuk mengelola inventaris kantor secara efisien. Aplikasi ini memungkinkan:

-   **Karyawan** dapat mengajukan permintaan barang melalui form yang user-friendly
-   **Operator Gudang** dapat mengelola stok barang, memproses permintaan, dan melacak riwayat barang masuk/keluar
-   **Tracking Otomatis** perubahan stok dengan sistem history yang komprehensif
-   **Reporting** untuk analisis penggunaan barang per periode

Aplikasi ini dikembangkan sebagai bagian dari **Uji Kompetensi** dengan fokus pada best practices pengembangan web modern.

---

## ✨ Fitur Utama

### 👩‍💼 Untuk Karyawan

-   ✅ Form permintaan barang yang mudah digunakan
-   ✅ Daftar stok barang tersedia
-   ✅ Riwayat permintaan pribadi
-   ✅ Status permintaan real-time (Pending, Selesai, Ditolak)

### ⚙️ Untuk Operator Gudang

-   ✅ Dashboard manajemen barang
-   ✅ CRUD Barang (Create, Read, Update, Delete)
-   ✅ Manajemen stok dengan kategori dan satuan
-   ✅ Proses permintaan barang dari karyawan
-   ✅ Pencatatan barang masuk/keluar
-   ✅ Riwayat lengkap perubahan stok
-   ✅ Trash management untuk permintaan (soft delete)
-   ✅ Export laporan ke PDF
-   ✅ Statistik penggunaan barang per bulan
-   ✅ Autentikasi & Otorisasi

---

## 🛠 Tech Stack

| Komponen           | Teknologi                   |
| ------------------ | --------------------------- |
| **Framework**      | Laravel 11                  |
| **Database**       | MySQL / MariaDB             |
| **Frontend**       | Blade Template, Bootstrap 5 |
| **Asset Pipeline** | Vite                        |
| **Authentication** | Laravel Authentication      |
| **ORM**            | Eloquent                    |
| **Testing**        | PHPUnit                     |

---

## 🗄 Schema Database

### 📊 Diagram Relasi Tabel

```
users
├── id (PK)
├── name
├── email
├── password
├── role (admin/operator)
└── timestamps

barangs
├── id (PK)
├── kode_barang (UNIQUE)
├── nama_barang
├── stok
├── satuan
├── kategori
├── deleted_at (soft delete)
└── timestamps

permintaans
├── id (PK)
├── nama_peminta
├── nama_ruangan
├── jumlah
├── status (pending/selesai/rejected)
├── keterangan
├── deleted_at (soft delete)
└── timestamps

permintaan_items
├── id (PK)
├── permintaan_id (FK → permintaans)
├── barang_id (FK → barangs)
├── jumlah
├── catatan
└── timestamps

barang_keluars
├── id (PK)
├── barang_id (FK → barangs)
├── jumlah_keluar
├── tanggal_keluar
└── timestamps

barang_histories
├── id (PK)
├── barang_id (FK → barangs)
├── type (created/stock_changed/other)
├── qty
├── stok_before
├── stok_after
├── note
└── timestamps
```

### 📑 Detail Tabel

#### **1. Tabel `users`**

Menyimpan informasi pengguna (karyawan dan operator)

| Kolom               | Tipe                 | Keterangan           |
| ------------------- | -------------------- | -------------------- |
| `id`                | BIGINT, PK           | Primary Key          |
| `name`              | VARCHAR(255)         | Nama pengguna        |
| `email`             | VARCHAR(255), UNIQUE | Email unik           |
| `password`          | VARCHAR(255)         | Password terenkripsi |
| `role`              | VARCHAR(50)          | admin atau operator  |
| `email_verified_at` | TIMESTAMP            | Verifikasi email     |
| `created_at`        | TIMESTAMP            | Dibuat pada          |
| `updated_at`        | TIMESTAMP            | Diupdate pada        |

---

#### **2. Tabel `barangs`**

Menyimpan data master barang di gudang

| Kolom         | Tipe                 | Keterangan                  |
| ------------- | -------------------- | --------------------------- |
| `id`          | BIGINT, PK           | Primary Key                 |
| `kode_barang` | VARCHAR(255), UNIQUE | Kode unik barang            |
| `nama_barang` | VARCHAR(255)         | Nama barang                 |
| `stok`        | INTEGER              | Jumlah stok saat ini        |
| `satuan`      | VARCHAR(100)         | Satuan (pcs, box, rim, dll) |
| `kategori`    | VARCHAR(100)         | Kategori barang             |
| `deleted_at`  | TIMESTAMP            | Soft delete timestamp       |
| `created_at`  | TIMESTAMP            | Dibuat pada                 |
| `updated_at`  | TIMESTAMP            | Diupdate pada               |

---

#### **3. Tabel `permintaans`**

Menyimpan data permintaan barang dari karyawan

| Kolom          | Tipe         | Keterangan                 |
| -------------- | ------------ | -------------------------- |
| `id`           | BIGINT, PK   | Primary Key                |
| `nama_peminta` | VARCHAR(255) | Nama karyawan peminta      |
| `nama_ruangan` | VARCHAR(255) | Ruangan/departemen         |
| `jumlah`       | INTEGER      | Total jumlah permintaan    |
| `status`       | VARCHAR(50)  | pending, selesai, rejected |
| `keterangan`   | TEXT         | Catatan (alasan penolakan) |
| `deleted_at`   | TIMESTAMP    | Soft delete timestamp      |
| `created_at`   | TIMESTAMP    | Dibuat pada                |
| `updated_at`   | TIMESTAMP    | Diupdate pada              |

---

#### **4. Tabel `permintaan_items`**

Menyimpan detail item-item dalam satu permintaan

| Kolom           | Tipe       | Keterangan                     |
| --------------- | ---------- | ------------------------------ |
| `id`            | BIGINT, PK | Primary Key                    |
| `permintaan_id` | BIGINT, FK | ID permintaan (CASCADE delete) |
| `barang_id`     | BIGINT, FK | ID barang (CASCADE delete)     |
| `jumlah`        | INTEGER    | Jumlah barang yang diminta     |
| `catatan`       | TEXT       | Catatan tambahan per item      |
| `created_at`    | TIMESTAMP  | Dibuat pada                    |
| `updated_at`    | TIMESTAMP  | Diupdate pada                  |

---

#### **5. Tabel `barang_keluars`**

Menyimpan catatan barang yang keluar dari gudang

| Kolom            | Tipe       | Keterangan                 |
| ---------------- | ---------- | -------------------------- |
| `id`             | BIGINT, PK | Primary Key                |
| `barang_id`      | BIGINT, FK | ID barang (CASCADE delete) |
| `jumlah_keluar`  | INTEGER    | Jumlah barang keluar       |
| `tanggal_keluar` | DATE       | Tanggal barang keluar      |
| `created_at`     | TIMESTAMP  | Dibuat pada                |
| `updated_at`     | TIMESTAMP  | Diupdate pada              |

---

#### **6. Tabel `barang_histories`**

Menyimpan riwayat perubahan stok untuk audit trail

| Kolom         | Tipe         | Keterangan                    |
| ------------- | ------------ | ----------------------------- |
| `id`          | BIGINT, PK   | Primary Key                   |
| `barang_id`   | BIGINT, FK   | ID barang (CASCADE delete)    |
| `type`        | VARCHAR(100) | created, stock_changed, other |
| `qty`         | INTEGER      | Jumlah perubahan              |
| `stok_before` | INTEGER      | Stok sebelum perubahan        |
| `stok_after`  | INTEGER      | Stok setelah perubahan        |
| `note`        | TEXT         | Catatan perubahan             |
| `created_at`  | TIMESTAMP    | Dibuat pada                   |
| `updated_at`  | TIMESTAMP    | Diupdate pada                 |

---

## 🚀 Instalasi

### Prasyarat

-   PHP 8.2+
-   Composer
-   MySQL/MariaDB
-   Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone https://github.com/RayanKhokonMiah15/UJIKOM-RAYAN.K-SYSTEM_ATK.git
    cd UJIKOM-RAYAN.K-SYSTEM_ATK
    ```

2. **Install Dependencies PHP**

    ```bash
    composer install
    ```

3. **Setup Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database**
   Edit file `.env`:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gudang_kantor
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Jalankan Migrasi**

    ```bash
    php artisan migrate
    ```

6. **Seed Database (Opsional)**

    ```bash
    php artisan db:seed
    ```

7. **Setup Storage Link**

    ```bash
    php artisan storage:link
    ```

8. **Install Frontend Dependencies**

    ```bash
    npm install
    npm run build
    ```

9. **Jalankan Server**

    ```bash
    php artisan serve
    ```

    Akses aplikasi di `http://localhost:8000`

---

## 📖 Penggunaan

### 🔐 Akun Default

-   **Email:** admin@example.com
-   **Password:** password
-   **Role:** Admin/Operator

### 👨‍💼 Alur Karyawan

1. Buka halaman utama (`/`)
2. Isi form permintaan barang
3. Pilih barang dan jumlah yang diinginkan
4. Submit permintaan
5. Tunggu approval dari operator

### ⚙️ Alur Operator

1. Login ke `/login` dengan akun operator
2. **Manajemen Barang**

    - Lihat daftar barang di `/operator/barang`
    - Tambah barang baru: `/operator/barang/create`
    - Edit atau hapus barang sesuai kebutuhan

3. **Proses Permintaan**

    - Lihat permintaan masuk di `/operator/permintaan`
    - Setujui atau tolak permintaan
    - Update status menjadi "selesai"

4. **Info Barang Masuk/Keluar**

    - Monitor barang masuk: `/operator/infobarang/masuk`
    - Monitor barang keluar: `/operator/infobarang/keluar`
    - Export laporan ke PDF

5. **Statistik & Laporan**
    - Lihat statistik penggunaan: `/operator/permintaan/stats`
    - Export laporan PDF: `/operator/permintaan/stats/export`

---

## 📁 Struktur Project

```
project/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controller untuk setiap fitur
│   │   │   ├── BarangController.php
│   │   │   ├── PermintaanController.php
│   │   │   ├── OperatorAuthController.php
│   │   │   └── InfoBarangController.php
│   │   └── Middleware/        # Custom middleware
│   ├── Models/                # Eloquent Models
│   │   ├── User.php
│   │   ├── Barang.php
│   │   ├── Permintaan.php
│   │   ├── PermintaanItem.php
│   │   ├── BarangKeluar.php
│   │   └── BarangHistory.php
│   └── Providers/
│
├── database/
│   ├── migrations/            # Database migrations
│   ├── factories/             # Model factories
│   └── seeders/               # Database seeders
│
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # Custom CSS
│   └── js/                    # Custom JavaScript
│
├── routes/
│   └── web.php                # Route definitions
│
├── public/                    # Public assets
├── storage/                   # File uploads & logs
├── config/                    # Configuration files
├── tests/                     # Unit & feature tests
└── vendor/                    # Composer dependencies
```

---

## 🔗 Relasi Model

### Barang Model

```php
hasMany(BarangKeluar::class)
hasMany(BarangHistory::class)
hasMany(PermintaanItem::class)
```

### Permintaan Model

```php
hasMany(PermintaanItem::class)
```

### PermintaanItem Model

```php
belongsTo(Permintaan::class)
belongsTo(Barang::class)
```

### BarangKeluar Model

```php
belongsTo(Barang::class)
```

### BarangHistory Model

```php
belongsTo(Barang::class)
```

---

## 📋 API Routes

### Karyawan

-   `GET /` - Form permintaan barang
-   `POST /permintaan` - Submit permintaan

### Operator

-   `GET /login` - Form login
-   `POST /login` - Proses login
-   `POST /logout` - Logout

#### Manajemen Barang

-   `GET /operator/barang` - Daftar barang
-   `GET /operator/barang/create` - Form tambah barang
-   `POST /operator/barang` - Simpan barang
-   `GET /operator/barang/{id}/edit` - Form edit barang
-   `PUT /operator/barang/{id}` - Update barang
-   `DELETE /operator/barang/{id}` - Hapus barang

#### Manajemen Permintaan

-   `GET /operator/permintaan` - Daftar permintaan
-   `PATCH /operator/permintaan/{id}/selesai` - Selesaikan permintaan
-   `POST /operator/permintaan/{id}/reject` - Tolak permintaan
-   `DELETE /operator/permintaan/{id}` - Hapus permintaan
-   `GET /operator/permintaan/trash` - Daftar permintaan terhapus
-   `POST /operator/permintaan/{id}/restore` - Pulihkan permintaan

#### Info Barang

-   `GET /operator/infobarang/masuk` - Barang masuk
-   `GET /operator/infobarang/keluar` - Barang keluar
-   `GET /operator/infobarang/masuk/export` - Export PDF masuk
-   `GET /operator/infobarang/keluar/export` - Export PDF keluar

#### Laporan

-   `GET /operator/permintaan/stats` - Statistik permintaan
-   `GET /operator/permintaan/stats/export` - Export statistik PDF

---

## 🎓 Best Practices yang Diterapkan

✅ **MVC Architecture** - Pemisahan yang jelas antara Model, View, dan Controller
✅ **Eloquent ORM** - Menggunakan ORM untuk query database yang aman
✅ **Route Model Binding** - Automatic dependency injection untuk models
✅ **Soft Delete** - Implementasi soft delete untuk data preservation
✅ **Foreign Keys** - Relasi database dengan constraint yang tepat
✅ **Migration & Seeding** - Version control untuk database schema
✅ **Authentication** - Sistem autentikasi yang aman
✅ **RESTful Routes** - Penamaan route yang konsisten dan terstruktur
✅ **Blade Templating** - Template engine yang powerful dan fleksibel
✅ **Validation** - Input validation untuk data integrity

---

## 📄 License

Project ini dikembangkan sebagai bagian dari Uji Kompetensi. Lihat file LICENSE untuk detail lebih lanjut.

---

## 👤 Developer

**Rayan Khokon Miah**

-   GitHub: [@RayanKhokonMiah15](https://github.com/RayanKhokonMiah15)
-   Email: rayan@example.com

---

## 📞 Support

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.

---

**Last Updated:** Desember 2, 2025

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
