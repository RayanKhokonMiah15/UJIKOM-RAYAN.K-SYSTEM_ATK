# 📦 Sistem Manajemen Barang Gudang Kantor

Sistem Manajemen Barang adalah aplikasi web berbasis Laravel 11 yang dirancang untuk mengelola stok barang dan memproses permintaan barang di lingkungan kantor atau gudang. Aplikasi ini memisahkan peran pengguna menjadi dua kategori: **Karyawan** (sebagai peminta barang) dan **Operator** (sebagai pengelola gudang).

---

## 📋 Daftar Isi

-   [Deskripsi Project](#-deskripsi-project)
-   [Fitur Utama](#-fitur-utama)
-   [Tech Stack](#-tech-stack)
-   [Use Case](#-use-case)
-   [Schema Database](#-schema-database)
-   [ERD & UML Diagram](#-erd--uml-diagram)
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

## 📌 Use Case

### 🎯 Use Case Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Sistem Manajemen Barang                  │
└─────────────────────────────────────────────────────────────┘
              ▲                                    ▲
              │                                    │
       ┌──────┴──────┐                    ┌────────┴────────┐
       │              │                    │                 │
    KARYAWAN      OPERATOR              DATABASE         SYSTEM
       │              │                    │                 │
       │              ├─── Lihat Barang ───┤                 │
       │              │                    │                 │
       ├─ Ajukan ────►├─── Proses Permintaan                 │
       │  Permintaan  │                    │                 │
       │              ├─── Kelola Stok ────┤                 │
       │              │                    │                 │
       │              ├─── Export Laporan ─┤                 │
       │              │                    │                 │
       │◄─ Notifikasi─┤                    ├─ Audit Trail ──┤
       │              │                    │                 │
       │◄─ Status ────┤                    │                 │
       │              │                    │                 │
```

### 📋 Detail Use Case

#### **1. UC-001: Ajukan Permintaan Barang**

**Actor:** Karyawan

**Deskripsi:** Karyawan mengajukan permintaan barang melalui form.

**Alur Normal:**

1. Karyawan membuka halaman permintaan barang
2. Memilih barang yang diinginkan dari dropdown
3. Mengisi nama peminta dan nama ruangan
4. Menentukan jumlah permintaan
5. Menandatangani secara digital
6. Sistem menyimpan permintaan dengan status "Pending"
7. Sistem mengirim notifikasi ke operator

**Hasil:** Permintaan barang tersimpan dalam database

---

#### **2. UC-002: Lihat Stok Barang**

**Actor:** Karyawan, Operator

**Deskripsi:** Pengguna dapat melihat daftar barang dan stok yang tersedia.

**Alur Normal:**

1. Pengguna membuka halaman stok barang / modal informasi
2. Sistem menampilkan daftar barang dengan detail:
    - Kode barang
    - Nama barang
    - Stok saat ini
    - Satuan
    - Kategori
    - Foto barang (jika ada)
3. Pengguna dapat mencari/filter barang

**Hasil:** Informasi stok barang ditampilkan dengan akurat

---

#### **3. UC-003: Kelola Barang (CRUD)**

**Actor:** Operator

**Deskripsi:** Operator mengelola master data barang.

**Alur - Create:**

1. Operator membuka halaman tambah barang
2. Mengisi form data barang:
    - Kode barang (unik)
    - Nama barang
    - Stok awal
    - Satuan
    - Kategori
    - Foto (opsional)
3. Sistem menyimpan data barang
4. Sistem membuat history "created"

**Alur - Read:**

1. Operator melihat daftar barang di tabel
2. Informasi ditampilkan dengan sorting/filter

**Alur - Update:**

1. Operator klik edit pada barang tertentu
2. Mengubah data barang
3. Sistem menyimpan perubahan
4. Sistem membuat history "stock_changed"

**Alur - Delete:**

1. Operator klik hapus pada barang tertentu
2. Sistem melakukan soft delete
3. Barang tidak tampil di list normal

---

#### **4. UC-004: Proses Permintaan Barang**

**Actor:** Operator

**Deskripsi:** Operator menerima dan memproses permintaan dari karyawan.

**Alur Normal:**

1. Operator membuka halaman daftar permintaan (status: Pending)
2. Memilih permintaan yang akan diproses
3. Operator dapat:
    - **Approve:** Cek stok, kurangi stok, buat catatan barang keluar, update status "Selesai"
    - **Reject:** Memberikan alasan penolakan, update status "Rejected"
4. Sistem membuat history perubahan stok
5. Sistem mengirim notifikasi ke peminta

**Hasil:** Permintaan diproses dan stok diperbarui

---

#### **5. UC-005: Catat Barang Masuk/Keluar**

**Actor:** Operator

**Deskripsi:** Operator mencatat barang yang masuk atau keluar dari gudang.

**Alur - Barang Keluar:**

1. Saat permintaan diapprove, sistem otomatis membuat record barang keluar
2. Record berisi:
    - ID Barang
    - Jumlah keluar
    - Tanggal keluar

**Alur - Barang Masuk:**

1. Operator dapat manual menambah stok barang
2. Melalui fitur edit barang
3. Sistem otomatis membuat history

**Hasil:** Catatan barang masuk/keluar tersimpan dan akurat

---

#### **6. UC-006: Lihat Riwayat Perubahan Stok**

**Actor:** Operator

**Deskripsi:** Operator melihat audit trail semua perubahan stok.

**Alur Normal:**

1. Operator membuka halaman "Info Barang Masuk/Keluar"
2. Sistem menampilkan history dengan detail:
    - Type perubahan (created, stock_changed, other)
    - Jumlah perubahan
    - Stok sebelum & sesudah
    - Catatan/alasan perubahan
    - Timestamp

**Alur Laporan:**

1. Operator dapat memfilter berdasarkan tanggal
2. Export laporan ke format PDF
3. Sistem generate laporan dengan format profesional

**Hasil:** Audit trail lengkap tersimpan dan dapat dilacak

---

#### **7. UC-007: Generate Laporan & Statistik**

**Actor:** Operator

**Deskripsi:** Operator membuat laporan penggunaan barang.

**Alur Normal:**

1. Operator membuka halaman statistik permintaan
2. Sistem menampilkan:
    - Jumlah permintaan per bulan
    - Barang paling banyak diminta
    - Status permintaan (pending, selesai, ditolak)
3. Operator dapat export laporan ke PDF
4. PDF berisi grafik dan tabel lengkap

**Hasil:** Laporan statistik tersimpan dan dapat dibagikan

---

#### **8. UC-008: Login Operator**

**Actor:** Operator

**Deskripsi:** Operator masuk ke sistem dengan kredensial.

**Alur Normal:**

1. Operator membuka halaman login
2. Mengisi email dan password
3. Sistem validasi kredensial
4. Jika valid → redirect ke dashboard operator
5. Jika invalid → tampilkan error

**Hasil:** Operator login dan dapat mengakses fitur operator

---

#### **9. UC-009: Restore Permintaan Terhapus**

**Actor:** Operator

**Deskripsi:** Operator dapat mengembalikan permintaan yang sudah dihapus (soft delete).

**Alur Normal:**

1. Operator membuka halaman "Trash Permintaan"
2. Melihat daftar permintaan yang sudah dihapus
3. Klik restore untuk mengembalikan
4. Permintaan kembali ke status sebelumnya
5. Sistem membuat record restore

**Hasil:** Permintaan berhasil di-restore dari trash

---

#### **10. UC-010: Tanda Tangan Digital**

**Actor:** Karyawan

**Deskripsi:** Karyawan menandatangani permintaan secara digital.

**Alur Normal:**

1. Karyawan membuka halaman permintaan
2. Di bagian "Tanda Tangan Peminta", ada canvas untuk menandatangan
3. Karyawan menandatangan menggunakan mouse/touchpad
4. Dapat klik "Hapus" untuk menghapus dan ulang
5. Saat submit, tanda tangan di-encode menjadi image dan disimpan

**Hasil:** Tanda tangan tersimpan sebagai bukti otentikasi permintaan

---

### 📊 Use Case Matrix

| Use Case | Karyawan | Operator | Database         | Description          |
| -------- | -------- | -------- | ---------------- | -------------------- |
| UC-001   | ✓        | -        | PERMINTAANS      | Ajukan Permintaan    |
| UC-002   | ✓        | ✓        | BARANGS          | Lihat Stok Barang    |
| UC-003   | -        | ✓        | BARANGS          | CRUD Barang          |
| UC-004   | -        | ✓        | PERMINTAANS      | Proses Permintaan    |
| UC-005   | -        | ✓        | BARANG_KELUARS   | Catat Barang Keluar  |
| UC-006   | -        | ✓        | BARANG_HISTORIES | Lihat History        |
| UC-007   | -        | ✓        | PERMINTAANS      | Generate Laporan     |
| UC-008   | -        | ✓        | USERS            | Login Operator       |
| UC-009   | -        | ✓        | PERMINTAANS      | Restore Permintaan   |
| UC-010   | ✓        | -        | PERMINTAANS      | Tanda Tangan Digital |

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

## 📐 ERD & UML Diagram

### 🎨 Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                                  USERS                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│ PK: id                                                                      │
│ • name VARCHAR(255)                                                         │
│ • email VARCHAR(255) UNIQUE                                                 │
│ • password VARCHAR(255)                                                     │
│ • role VARCHAR(50)                                                          │
│ • timestamps                                                                │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│                       BARANGS                                │
├──────────────────────────────────────────────────────────────┤
│ PK: id                                                       │
│ • kode_barang VARCHAR(255) UNIQUE                            │
│ • nama_barang VARCHAR(255)                                   │
│ • stok INTEGER                                               │
│ • satuan VARCHAR(100)                                        │
│ • kategori VARCHAR(100)                                      │
│ • deleted_at TIMESTAMP (soft delete)                         │
│ • timestamps                                                 │
└──────────────────────────────────────────────────────────────┘
      ▲              ▲                      ▲
      │ 1:N          │ 1:N                  │ 1:N
      │              │                      │
  ┌───┴──────────────┼──────────────────────┴────────┐
  │                  │                                 │
┌─┴────────────────────────────────────────┐  ┌────────────────────────┐
│      PERMINTAAN_ITEMS                    │  │   BARANG_KELUARS       │
├──────────────────────────────────────────┤  ├────────────────────────┤
│ PK: id                                   │  │ PK: id                 │
│ FK: permintaan_id (CASCADE DELETE)       │  │ FK: barang_id          │
│ FK: barang_id (CASCADE DELETE)           │  │ • jumlah_keluar INT    │
│ • jumlah INTEGER                         │  │ • tanggal_keluar DATE  │
│ • catatan TEXT                           │  │ • timestamps           │
│ • timestamps                             │  └────────────────────────┘
└────┬──────────────────────────┬──────────┘
     │ belongsTo                │
     │ N:1                      │
     │                          │
     ▼                          ▼
┌──────────────────────┐  ┌──────────────────────────┐
│    PERMINTAANS       │  │  BARANG_HISTORIES        │
├──────────────────────┤  ├──────────────────────────┤
│ PK: id               │  │ PK: id                   │
│ • nama_peminta       │  │ FK: barang_id            │
│ • nama_ruangan       │  │ • type VARCHAR(100)      │
│ • jumlah INTEGER     │  │ • qty INTEGER            │
│ • status VARCHAR(50) │  │ • stok_before INTEGER    │
│ • keterangan TEXT    │  │ • stok_after INTEGER     │
│ • deleted_at         │  │ • note TEXT              │
│ • timestamps         │  │ • timestamps             │
└──────────────────────┘  └──────────────────────────┘
     1:N (hasMany)
      ▲
      │
  PERMINTAAN_ITEMS
  (junction table)
```

### 📊 Penjelasan Relasi Antar Tabel

| Relasi                         | Cardinality | Constraint     | Keterangan                         |
| ------------------------------ | ----------- | -------------- | ---------------------------------- |
| BARANGS → PERMINTAAN_ITEMS     | 1:N         | CASCADE DELETE | 1 barang bisa diminta berkali-kali |
| BARANGS → BARANG_KELUARS       | 1:N         | CASCADE DELETE | 1 barang bisa keluar berkali-kali  |
| BARANGS → BARANG_HISTORIES     | 1:N         | CASCADE DELETE | 1 barang punya banyak history      |
| PERMINTAANS → PERMINTAAN_ITEMS | 1:N         | CASCADE DELETE | 1 permintaan punya banyak item     |

### 🏗 UML Class Diagram

```
┌─────────────────────────────────────┐
│            <<Model>>                │
│            User                     │
├─────────────────────────────────────┤
│ - id: int                           │
│ - name: string                      │
│ - email: string                     │
│ - password: string                  │
│ - role: string                      │
├─────────────────────────────────────┤
│ + authenticate()                    │
│ + hasPermission()                   │
│ + logout()                          │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│            <<Model>>                │
│            Barang                   │
├─────────────────────────────────────┤
│ - id: int                           │
│ - kode_barang: string (unique)      │
│ - nama_barang: string               │
│ - stok: int                         │
│ - satuan: string                    │
│ - kategori: string                  │
├─────────────────────────────────────┤
│ + getStok(): int                    │
│ + updateStok(qty): void             │
│ + getHistory(): Collection          │
│ + getOutgoing(): Collection         │
└──────┬──────────────────────────────┘
       │
       │ hasMany (1:N)
       ├─────────────────────┬──────────────────────┐
       │                     │                      │
       ▼                     ▼                      ▼
┌──────────────────┐ ┌──────────────────┐ ┌──────────────────────┐
│  <<Model>>       │ │  <<Model>>       │ │  <<Model>>           │
│ BarangKeluar     │ │BarangHistory     │ │PermintaanItem        │
├──────────────────┤ ├──────────────────┤ ├──────────────────────┤
│ - id: int        │ │ - id: int        │ │ - id: int            │
│ - barang_id: FK  │ │ - barang_id: FK  │ │ - permintaan_id: FK  │
│ - jumlah_keluar  │ │ - type: string   │ │ - barang_id: FK      │
│ - tanggal_keluar │ │ - qty: int       │ │ - jumlah: int        │
│                  │ │ - stok_before    │ │ - catatan: string    │
│                  │ │ - stok_after     │ │                      │
│                  │ │ - note: string   │ │                      │
├──────────────────┤ ├──────────────────┤ ├──────────────────────┤
│ + getBarang()    │ │ + getBarang()    │ │ + getBarang()        │
│ + delete()       │ │ + getChanges()   │ │ + getPermintaan()    │
└──────────────────┘ └──────────────────┘ └──────────┬───────────┘
                                                      │
                                                      │ belongsTo (N:1)
                                                      │
                                                      ▼
                                         ┌──────────────────────┐
                                         │  <<Model>>           │
                                         │  Permintaan          │
                                         ├──────────────────────┤
                                         │ - id: int            │
                                         │ - nama_peminta       │
                                         │ - nama_ruangan       │
                                         │ - jumlah: int        │
                                         │ - status: string     │
                                         │ - keterangan: string │
                                         ├──────────────────────┤
                                         │ + submit()           │
                                         │ + approve()          │
                                         │ + reject()           │
                                         │ + getItems()         │
                                         │ + getStatus()        │
                                         │ + delete()           │
                                         │ + restore()          │
                                         └──────────────────────┘
```

### 🔄 Activity Diagram - Alur Permintaan Barang

```
                           Start
                            ▼
                  ┌─────────────────┐
                  │  Buka Form      │
                  │  Permintaan     │
                  └────────┬────────┘
                           ▼
                  ┌─────────────────┐
                  │  Isi Data       │
                  │  Permintaan     │
                  └────────┬────────┘
                           ▼
                  ┌─────────────────┐
                  │  Pilih Barang   │
                  │  & Jumlah       │
                  └────────┬────────┘
                           ▼
                  ┌─────────────────┐
                  │  Konfirmasi &   │
                  │  Submit         │
                  └────────┬────────┘
                           ▼
                  ┌─────────────────┐
                  │  Simpan di DB   │
                  │  (Pending)      │
                  └────────┬────────┘
                           ▼
            ┌──────────────────────────────┐
            │  Operator Lihat Permintaan   │
            └──────┬───────────────────────┘
                   ▼
        ┌──────────────────────┐
        │  Cek Stok Barang     │
        └──────┬───────────────┘
               ▼
         ┌──────────┐
         │  Stok    │
         │  Cukup?  │
         └─┬────┬───┘
           │    │
         YA│    │TIDAK
           │    │
           ▼    ▼
        ┌──┐ ┌─────────────┐
        │✓ │ │  Tolak      │
        │  │ │  Permintaan │
        └──┘ │  (Rejected) │
            └──────┬──────┘
                   ▼
             ┌─────────────┐
             │ Update      │
             │ Status      │
             └──────┬──────┘
                    ▼
             ┌─────────────┐
             │  Kurangi    │
             │  Stok       │
             └──────┬──────┘
                    ▼
             ┌─────────────┐
             │  Buat       │
             │  BarangKlr  │
             └──────┬──────┘
                    ▼
             ┌─────────────┐
             │  Buat       │
             │  History    │
             └──────┬──────┘
                    ▼
             ┌─────────────┐
             │  Update     │
             │  Selesai    │
             └──────┬──────┘
                    ▼
                   End
```

### 📋 Sequence Diagram - Proses Approval Permintaan

```
Karyawan          Aplikasi       Database      Operator
   │                │               │             │
   │──(1) Submit────→│               │             │
   │   Permintaan    │               │             │
   │                │               │             │
   │                │─(2) Save──────→│             │
   │                │   Permintaan   │             │
   │                │               │             │
   │                │               │             │
   │                │─(3) Notify────────────────→│
   │                │   (New Request)             │
   │                │               │             │
   │                │               │  (4) Check │
   │                │               │  Status◄─────
   │                │               │             │
   │                │  (5) Get──────→│             │
   │                │  Permintaan    │             │
   │                │◄──────────────│             │
   │                │   Data        │             │
   │                │               │             │
   │                │  (6) Get──────→│             │
   │                │  Barang Stok   │             │
   │                │◄──────────────│             │
   │                │               │             │
   │                │               │  (7) Approve
   │                │               │  & Update───→
   │                │               │             │
   │                │  (8) Update───→│             │
   │                │  Status        │             │
   │                │  Selesai       │             │
   │                │               │             │
   │◄─(9) Notify────│               │             │
   │   (Approved)   │               │             │
   │                │               │             │
```

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
-   **Password:** password123
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
-   Email: raykhmiah@gmail.com

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
