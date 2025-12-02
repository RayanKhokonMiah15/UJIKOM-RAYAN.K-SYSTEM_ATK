# 📊 Entity Relationship Diagram (ERD)

## Sistem Manajemen Barang Gudang Kantor

---

## 🎯 Deskripsi Umum

Sistem Manajemen Barang menggunakan 6 tabel utama yang saling terhubung melalui foreign key relationships. Database dirancang untuk menangani:

-   Manajemen master data barang
-   Proses permintaan barang dari karyawan
-   Tracking barang masuk dan keluar
-   Audit trail perubahan stok

---

## 📐 Diagram ERD (ASCII)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                                  USERS                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                                   │
│ • name                                                                      │
│ • email (UNIQUE)                                                           │
│ • password                                                                  │
│ • role                                                                      │
│ • timestamps                                                                │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                           BARANGS                                    │
├──────────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                            │
│ • kode_barang (UNIQUE)                                              │
│ • nama_barang                                                        │
│ • stok                                                               │
│ • satuan                                                             │
│ • kategori                                                           │
│ • deleted_at (soft delete)                                          │
│ • timestamps                                                         │
└──────────────────────────────────────────────────────────────────────┘
         ▲                    ▲                      ▲
         │ 1:N                │ 1:N                  │ 1:N
         │                    │                      │
         │ belongsTo          │ hasMany              │ hasMany
         │                    │                      │
    ┌────┴──────────────┐     │         ┌────────────┴──────────┐
    │                   │     │         │                       │
┌───┴────────────────────────────────────┐  ┌──────────────────────────┐
│      PERMINTAAN_ITEMS                  │  │   BARANG_KELUARS         │
├────────────────────────────────────────┤  ├──────────────────────────┤
│ • id (PK)                              │  │ • id (PK)                │
│ • permintaan_id (FK)                   │  │ • barang_id (FK)         │
│ • barang_id (FK)                       │  │ • jumlah_keluar          │
│ • jumlah                               │  │ • tanggal_keluar         │
│ • catatan                              │  │ • timestamps             │
│ • timestamps                           │  └──────────────────────────┘
└────┬──────────────────────────┬────────┘
     │                          │
     │ belongsTo               │ belongsTo
     │ 1:N                     │ 1:N
     │                         │
     ▼                         ▼
┌──────────────────────┐  ┌──────────────────────────┐
│    PERMINTAANS       │  │  BARANG_HISTORIES        │
├──────────────────────┤  ├──────────────────────────┤
│ • id (PK)            │  │ • id (PK)                │
│ • nama_peminta       │  │ • barang_id (FK)         │
│ • nama_ruangan       │  │ • type                   │
│ • jumlah             │  │ • qty                    │
│ • status             │  │ • stok_before            │
│ • keterangan         │  │ • stok_after             │
│ • deleted_at         │  │ • note                   │
│ • timestamps         │  │ • timestamps             │
└──────────────────────┘  └──────────────────────────┘
```

---

## 📋 Tabel dan Relasi Detail

### 1. **USERS** 👤

**Menyimpan:** Informasi pengguna (karyawan dan operator)

| Kolom             | Tipe         | Constraint         | Keterangan             |
| ----------------- | ------------ | ------------------ | ---------------------- |
| id                | BIGINT       | PK, AUTO_INCREMENT | Primary Key            |
| name              | VARCHAR(255) | -                  | Nama pengguna          |
| email             | VARCHAR(255) | UNIQUE, NOT NULL   | Email unik untuk login |
| password          | VARCHAR(255) | NOT NULL           | Password terenkripsi   |
| role              | VARCHAR(50)  | -                  | admin/operator/user    |
| email_verified_at | TIMESTAMP    | NULLABLE           | Verifikasi email       |
| created_at        | TIMESTAMP    | -                  | Waktu dibuat           |
| updated_at        | TIMESTAMP    | -                  | Waktu diupdate         |

**Relasi:**

-   Tidak memiliki foreign key (independent table)
-   Bisa diasosiasikan dengan tabel lain jika diperlukan

---

### 2. **BARANGS** 📦

**Menyimpan:** Master data barang di gudang

| Kolom       | Tipe         | Constraint         | Keterangan             |
| ----------- | ------------ | ------------------ | ---------------------- |
| id          | BIGINT       | PK, AUTO_INCREMENT | Primary Key            |
| kode_barang | VARCHAR(255) | UNIQUE, NOT NULL   | Kode identitas barang  |
| nama_barang | VARCHAR(255) | NOT NULL           | Nama barang            |
| stok        | INTEGER      | NOT NULL           | Jumlah stok saat ini   |
| satuan      | VARCHAR(100) | NULLABLE           | Satuan (pcs, box, rim) |
| kategori    | VARCHAR(100) | NULLABLE           | Kategori barang        |
| deleted_at  | TIMESTAMP    | NULLABLE           | Soft delete            |
| created_at  | TIMESTAMP    | -                  | Waktu dibuat           |
| updated_at  | TIMESTAMP    | -                  | Waktu diupdate         |

**Relasi:**

-   **1:N (One-to-Many)** → PERMINTAAN_ITEMS
    -   1 barang bisa diminta berkali-kali
    -   CASCADE DELETE (jika barang dihapus, item permintaan ikut terhapus)
-   **1:N (One-to-Many)** → BARANG_KELUARS
    -   1 barang bisa keluar berkali-kali
    -   CASCADE DELETE (jika barang dihapus, catatan keluar ikut terhapus)
-   **1:N (One-to-Many)** → BARANG_HISTORIES
    -   1 barang bisa memiliki banyak history perubahan stok
    -   CASCADE DELETE (jika barang dihapus, history ikut terhapus)

---

### 3. **PERMINTAANS** 📝

**Menyimpan:** Data permintaan barang dari karyawan

| Kolom        | Tipe         | Constraint         | Keterangan                 |
| ------------ | ------------ | ------------------ | -------------------------- |
| id           | BIGINT       | PK, AUTO_INCREMENT | Primary Key                |
| nama_peminta | VARCHAR(255) | NOT NULL           | Nama karyawan peminta      |
| nama_ruangan | VARCHAR(255) | NOT NULL           | Departemen/ruangan         |
| jumlah       | INTEGER      | NOT NULL           | Total jumlah item          |
| status       | VARCHAR(50)  | NOT NULL           | pending/selesai/rejected   |
| keterangan   | TEXT         | NULLABLE           | Catatan (alasan penolakan) |
| deleted_at   | TIMESTAMP    | NULLABLE           | Soft delete                |
| created_at   | TIMESTAMP    | -                  | Waktu dibuat               |
| updated_at   | TIMESTAMP    | -                  | Waktu diupdate             |

**Relasi:**

-   **1:N (One-to-Many)** → PERMINTAAN_ITEMS
    -   1 permintaan bisa terdiri dari banyak item barang
    -   CASCADE DELETE (jika permintaan dihapus, item-itemnya ikut terhapus)

---

### 4. **PERMINTAAN_ITEMS** 🔗

**Menyimpan:** Detail item-item dalam satu permintaan

| Kolom         | Tipe      | Constraint         | Keterangan                 |
| ------------- | --------- | ------------------ | -------------------------- |
| id            | BIGINT    | PK, AUTO_INCREMENT | Primary Key                |
| permintaan_id | BIGINT    | FK (permintaans)   | Relasi ke tabel permintaan |
| barang_id     | BIGINT    | FK (barangs)       | Relasi ke tabel barang     |
| jumlah        | INTEGER   | NOT NULL           | Jumlah barang diminta      |
| catatan       | TEXT      | NULLABLE           | Catatan spesifik per item  |
| created_at    | TIMESTAMP | -                  | Waktu dibuat               |
| updated_at    | TIMESTAMP | -                  | Waktu diupdate             |

**Relasi:**

-   **N:1 (Many-to-One)** → PERMINTAANS
    -   Banyak item bisa berada di 1 permintaan
    -   Foreign Key: `permintaan_id`
    -   CASCADE DELETE
-   **N:1 (Many-to-One)** → BARANGS
    -   Banyak item permintaan bisa mereferensi 1 barang
    -   Foreign Key: `barang_id`
    -   CASCADE DELETE

---

### 5. **BARANG_KELUARS** 📤

**Menyimpan:** Catatan barang yang keluar dari gudang

| Kolom          | Tipe      | Constraint         | Keterangan                |
| -------------- | --------- | ------------------ | ------------------------- |
| id             | BIGINT    | PK, AUTO_INCREMENT | Primary Key               |
| barang_id      | BIGINT    | FK (barangs)       | Relasi ke tabel barang    |
| jumlah_keluar  | INTEGER   | NOT NULL           | Jumlah barang yang keluar |
| tanggal_keluar | DATE      | NOT NULL           | Tanggal barang keluar     |
| created_at     | TIMESTAMP | -                  | Waktu dibuat              |
| updated_at     | TIMESTAMP | -                  | Waktu diupdate            |

**Relasi:**

-   **N:1 (Many-to-One)** → BARANGS
    -   Banyak catatan keluar bisa mereferensi 1 barang
    -   Foreign Key: `barang_id`
    -   CASCADE DELETE (jika barang dihapus, catatan keluar ikut terhapus)

---

### 6. **BARANG_HISTORIES** 📊

**Menyimpan:** Riwayat/audit trail perubahan stok barang

| Kolom       | Tipe         | Constraint         | Keterangan             |
| ----------- | ------------ | ------------------ | ---------------------- |
| id          | BIGINT       | PK, AUTO_INCREMENT | Primary Key            |
| barang_id   | BIGINT       | FK (barangs)       | Relasi ke tabel barang |
| type        | VARCHAR(100) | NOT NULL           | Tipe perubahan         |
| qty         | INTEGER      | NULLABLE           | Jumlah perubahan       |
| stok_before | INTEGER      | NULLABLE           | Stok sebelum perubahan |
| stok_after  | INTEGER      | NULLABLE           | Stok setelah perubahan |
| note        | TEXT         | NULLABLE           | Catatan perubahan      |
| created_at  | TIMESTAMP    | -                  | Waktu dibuat           |
| updated_at  | TIMESTAMP    | -                  | Waktu diupdate         |

**Relasi:**

-   **N:1 (Many-to-One)** → BARANGS
    -   Banyak history bisa mereferensi 1 barang
    -   Foreign Key: `barang_id`
    -   CASCADE DELETE (jika barang dihapus, history ikut terhapus)

**Type Values:**

-   `created` - Barang baru diciptakan
-   `stock_changed` - Stok berubah
-   `other` - Perubahan lainnya

---

## 🔀 Penjelasan Relasi Antar Tabel

### **1. Relasi One-to-Many (1:N)**

```
BARANGS (1) ──────→ PERMINTAAN_ITEMS (N)
  ▲                      │
  │                      └──→ PERMINTAANS
  │
  │ 1 barang bisa:
  │ • Diminta berkali-kali oleh karyawan berbeda
  │ • Keluar berkali-kali dengan jumlah berbeda
  │ • Memiliki banyak catatan history perubahan stok
```

**Contoh:**

-   Barang "Kertas A4" (id=1) diminta pada:
    -   Permintaan #1 (tgl 1 Desember) - 5 rim
    -   Permintaan #2 (tgl 2 Desember) - 3 rim
    -   Permintaan #3 (tgl 3 Desember) - 2 rim

---

### **2. Relasi Many-to-Many (N:M) melalui Junction Table**

```
PERMINTAANS (1) ──→ PERMINTAAN_ITEMS (N) ←── BARANGS (1)
                    (Junction/Bridge Table)

Satu permintaan bisa berisi banyak barang
Satu barang bisa diminta dalam banyak permintaan
```

**Contoh:**

```
Permintaan #1 (dari Bagian Admin):
├── Kertas A4 - 5 rim
├── Amplop - 10 pack
└── Sticky notes - 20 pad

Permintaan #2 (dari Bagian Finance):
├── Kertas A4 - 3 rim    ← Barang yang sama, permintaan berbeda
└── Spidol - 12 box
```

---

### **3. Cascade Delete**

Semua foreign key menggunakan **CASCADE DELETE**, artinya:

```
Jika BARANGS dengan id=1 dihapus, maka:
├── Semua PERMINTAAN_ITEMS dengan barang_id=1 ikut terhapus
├── Semua BARANG_KELUARS dengan barang_id=1 ikut terhapus
└── Semua BARANG_HISTORIES dengan barang_id=1 ikut terhapus

Jika PERMINTAANS dengan id=1 dihapus, maka:
└── Semua PERMINTAAN_ITEMS dengan permintaan_id=1 ikut terhapus
```

---

## 🔄 Alur Data dalam Sistem

### **Alur Permintaan Barang:**

```
1. KARYAWAN membuat permintaan
   ↓
2. PERMINTAANS record dibuat (status: pending)
   ↓
3. Karyawan memilih N barang → PERMINTAAN_ITEMS record dibuat
   ├── Item 1: Barang A, jumlah 5
   ├── Item 2: Barang B, jumlah 10
   └── Item 3: Barang C, jumlah 3
   ↓
4. OPERATOR menerima & memproses
   ├── Verifikasi stok BARANGS
   ├── Kurangi stok BARANGS
   ├── Buat BARANG_KELUARS record
   ├── Buat BARANG_HISTORIES record (type: stock_changed)
   └── Update status PERMINTAANS (selesai)
   ↓
5. Sistem mencatat history perubahan stok di BARANG_HISTORIES
```

---

## 📈 Contoh Data Relasi

### **Skenario: Permintaan Kertas Kantor**

**BARANGS:**

```
id=1, kode_barang="KRT001", nama_barang="Kertas A4",
stok=100, satuan="rim", kategori="Alat Tulis"
```

**PERMINTAANS:**

```
id=101, nama_peminta="Budi", nama_ruangan="Admin",
jumlah=15, status="pending"
```

**PERMINTAAN_ITEMS:**

```
id=1001, permintaan_id=101, barang_id=1, jumlah=15
```

**BARANG_KELUARS (setelah disetujui):**

```
id=501, barang_id=1, jumlah_keluar=15, tanggal_keluar="2025-12-02"
```

**BARANG_HISTORIES (audit trail):**

```
id=2001, barang_id=1, type="stock_changed", qty=-15,
stok_before=100, stok_after=85,
note="Keluar karena permintaan dari Bagian Admin"
```

**BARANGS (updated):**

```
id=1, kode_barang="KRT001", nama_barang="Kertas A4",
stok=85, satuan="rim", kategori="Alat Tulis"  ← Stok berkurang
```

---

## 🛡️ Integritas Data

### **Primary Keys (PK)**

-   Setiap tabel memiliki `id` sebagai primary key
-   AUTO_INCREMENT untuk otomatis
-   Unik dan tidak boleh NULL

### **Foreign Keys (FK)**

-   PERMINTAAN_ITEMS.permintaan_id → PERMINTAANS.id
-   PERMINTAAN_ITEMS.barang_id → BARANGS.id
-   BARANG_KELUARS.barang_id → BARANGS.id
-   BARANG_HISTORIES.barang_id → BARANGS.id

### **Unique Constraints**

-   USERS.email - Email harus unik
-   BARANGS.kode_barang - Kode barang harus unik

### **Soft Delete**

-   BARANGS.deleted_at
-   PERMINTAANS.deleted_at
-   Memungkinkan data recovery tanpa harddelete

---

## 📊 Cardinality Summary

| Relasi                         | Type | Cardinality            | Constraint |
| ------------------------------ | ---- | ---------------------- | ---------- |
| BARANGS → PERMINTAAN_ITEMS     | 1:N  | 1 barang : N items     | CASCADE    |
| BARANGS → BARANG_KELUARS       | 1:N  | 1 barang : N keluar    | CASCADE    |
| BARANGS → BARANG_HISTORIES     | 1:N  | 1 barang : N history   | CASCADE    |
| PERMINTAANS → PERMINTAAN_ITEMS | 1:N  | 1 permintaan : N items | CASCADE    |

---

## 🔗 SQL Relationship Definitions

### **Foreign Key Definitions:**

```sql
-- PERMINTAAN_ITEMS
ALTER TABLE permintaan_items
ADD CONSTRAINT fk_permintaan_items_permintaan
FOREIGN KEY (permintaan_id) REFERENCES permintaans(id) ON DELETE CASCADE;

ALTER TABLE permintaan_items
ADD CONSTRAINT fk_permintaan_items_barang
FOREIGN KEY (barang_id) REFERENCES barangs(id) ON DELETE CASCADE;

-- BARANG_KELUARS
ALTER TABLE barang_keluars
ADD CONSTRAINT fk_barang_keluars_barang
FOREIGN KEY (barang_id) REFERENCES barangs(id) ON DELETE CASCADE;

-- BARANG_HISTORIES
ALTER TABLE barang_histories
ADD CONSTRAINT fk_barang_histories_barang
FOREIGN KEY (barang_id) REFERENCES barangs(id) ON DELETE CASCADE;
```

---

## 💡 Best Practices Diterapkan

✅ **Normalization** - Database dalam bentuk 3NF
✅ **Referential Integrity** - Foreign key dengan cascade delete
✅ **Audit Trail** - BARANG_HISTORIES untuk tracking perubahan
✅ **Soft Delete** - Data tidak hilang, bisa di-restore
✅ **Unique Identifiers** - Kode barang unik
✅ **Timestamp Tracking** - created_at & updated_at di semua tabel
✅ **Data Validation** - NOT NULL constraints

---

**Last Updated:** Desember 2, 2025
