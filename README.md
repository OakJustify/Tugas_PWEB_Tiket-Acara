# Aplikasi Pemesanan Tiket Acara

Aplikasi web untuk memesan tiket acara. Pengguna mengisi form (nama pemesan,
kelas tiket, jumlah tiket), lalu pesanan disimpan ke database dan ditampilkan
di halaman sebagai riwayat beserta total omzet.

Dibangun dengan **PHP + SQLite + Bootstrap 5 + jQuery**, tanpa framework dan
tanpa perlu server database terpisah.

---

## Daftar Isi

1. [Fitur](#1-fitur)
2. [Teknologi](#2-teknologi-yang-dipakai)
3. [Struktur Folder](#3-struktur-folder)
4. [Cara Menjalankan](#4-cara-menjalankan)
5. [Cara Kerja Aplikasi](#5-cara-kerja-aplikasi)
6. [Database](#6-database)
7. [Keamanan](#7-keamanan)
8. [Troubleshooting](#8-troubleshooting)
9. [Tips Presentasi](#9-tips-presentasi)

---

## 1. Fitur

| Fitur | Keterangan |
|---|---|
| Form pemesanan | Nama pemesan, pilih kelas tiket, jumlah tiket (1–10) |
| Hitung total otomatis | Total harga dihitung langsung di browser tanpa reload |
| Simpan ke database | Setiap pesanan yang valid tersimpan permanen |
| Riwayat pemesanan | Tabel menampilkan semua pesanan, terbaru di atas |
| Statistik | Total jumlah pesanan & total omzet, dihitung otomatis |
| Validasi 3 lapis | HTML5 + JavaScript + PHP (server) |
| Anti SQL Injection | Prepared statement dengan named parameter |
| Anti XSS | Output di-escape dengan `htmlspecialchars()` |

---

## 2. Teknologi yang Dipakai

| Teknologi | Versi | Untuk apa |
|---|---|---|
| **PHP** | 8.5 | Bahasa pemrograman sisi server |
| **SQLite** | bawaan PHP | Database (berupa 1 file) |
| **PDO** | bawaan PHP | Cara PHP terhubung ke database |
| **Bootstrap** | 5.3.3 | Layout, styling, dan komponen UI |
| **jQuery** | 3.7.1 | Hitung total live + validasi di browser |

**Kenapa SQLite?** Karena SQLite **tidak butuh server database**. Database-nya
hanya **satu file** (`database/ticket-acara.sqlite`). Tidak ada daemon yang
harus dijalankan, tidak ada username/password yang harus diatur, dan
aplikasinya bisa langsung dijalankan di komputer/laptop mana saja.

**Kenapa Bootstrap & jQuery disimpan lokal?** Supaya aplikasi tetap jalan
walaupun tidak ada koneksi internet — penting saat presentasi.

---

## 3. Struktur Folder

```
web-uts-pweb-ticket-acara/
├── index.php            Halaman utama: logika bisnis + tampilan
├── config.php           Koneksi database + membuat tabel otomatis
├── router.php           Mencegah file database diunduh (mode php -S)
├── tiket.sql            Skema tabel + data contoh
├── script.js            Hitung total live + validasi di browser
├── style.css            Style khusus (selebihnya pakai Bootstrap)
├── bootstrap.min.css    Bootstrap 5.3.3 (lokal, tanpa internet)
├── jquery.min.js        jQuery 3.7.1 (lokal, tanpa internet)
├── .htaccess            Proteksi file database (mode Apache/XAMPP)
├── .vscode/             Konfigurasi VS Code
│   ├── tasks.json         Task "Jalankan PHP Server"
│   └── settings.json      Path PHP untuk linting
├── database/            Folder file database
│   └── ticket-acara.sqlite
└── README.md            File ini
```

**Peran tiap file utama:**

| File | Isi | Jumlah baris |
|---|---|---|
| `index.php` | Validasi → hitung harga → simpan → redirect → tampilkan | 233 |
| `config.php` | Koneksi PDO + cek & buat tabel kalau belum ada | 60 |
| `router.php` | Menolak permintaan ke `/database/` | 26 |
| `script.js` | Hitung total + validasi browser | 53 |
| `style.css` | Warna latar, kartu, kotak tiket | 110 |
| `tiket.sql` | `CREATE TABLE` + 4 data contoh | 30 |

---

## 4. Cara Menjalankan

### Cara 1 — Dari Terminal ⭐

Buka terminal di folder proyek, lalu jalankan **satu perintah**:

```bash
cd ~/Projects/03-projects/web-uts-pweb-ticket-acara
php -S localhost:8000 -t . router.php
```

Buka **http://localhost:8000** di browser.

Penjelasan singkat:

| Bagian | Arti |
|---|---|
| `php -S` | Menjalankan **server bawaan PHP** (tanpa perlu Apache/nginx) |
| `localhost:8000` | Alamat server, port 8000 |
| `-t .` | Folder yang disajikan adalah folder ini |
| `router.php` | File pengatur: menolak akses ke folder `database/` |

Tekan `Ctrl+C` untuk menghentikan server.

> **Syarat:** PHP 8.5 + ekstensi `pdo_sqlite` sudah terpasang.
> Jika belum: `sudo pacman -S php php-sqlite`
> Lalu aktifkan ekstensinya: `sudo sed -i 's/^;extension=pdo_sqlite/extension=pdo_sqlite/' /etc/php/php.ini`

---

### Cara 2 — Dari VS Code / VSCodium

Sudah disiapkan **Task** di `.vscode/tasks.json`, jadi cukup satu klik:

1. Buka folder proyek ini di VS Code / VSCodium
   (*File → Open Folder…*)
2. Tekan **`Ctrl+Shift+B`**
   (atau menu *Terminal → Run Task…*)
3. Pilih **"Jalankan PHP Server"**
4. Buka **http://localhost:8000**

Task tersebut menjalankan `php -S localhost:8000 -t . router.php` di terminal
terpisah, dengan `cwd` = folder proyek.

**Tidak perlu extension apa pun.** Fitur *Task* sudah bawaan VS Code dan
VSCodium.

> **Catatan:** extension *"Live Server"* yang biasa dipakai untuk web statis
> **tidak bisa menjalankan PHP** — file `.php`-nya hanya diunduh sebagai teks.
> Extension *"PHP Server"* memang bisa, tapi tidak tersedia di Open VSX
> (marketplace bawaan VSCodium).

---

### Cara 3 — Windows / XAMPP (opsional)

```
1. Install XAMPP dari https://www.apachefriends.org
2. Copy folder ini ke   C:\xampp\htdocs\ticket-acara\
3. XAMPP Control Panel -> Start Apache  (MySQL tidak perlu dinyalakan!)
4. Buka http://localhost/ticket-acara/
```

Ekstensi `pdo_sqlite` sudah bawaan XAMPP, jadi tidak perlu konfigurasi apa pun.
Di mode ini, proteksi file database dipakai `.htaccess` (bukan `router.php`).

---

### Cek cepat apakah sudah siap

```bash
php -r 'require "config.php";
$r = $koneksi->query("SELECT COUNT(*) AS j, SUM(total_harga) AS o FROM tb_pemesanan")->fetch();
echo "Koneksi OK, data: {$r["j"]} pesanan, omzet Rp {$r["o"]}\n";'
```

Kalau keluar `Koneksi OK, data: 4 pesanan, omzet Rp 290000` → siap dipakai.

---

## 5. Cara Kerja Aplikasi

### 5.1 Alur saat halaman dibuka (GET)

```
Browser ──GET /index.php──► PHP
                               │
  1. require 'config.php'      │  → sambung ke SQLite
                               │  → cek tabel ada? tidak → buat tabel + isi data contoh
                               │
  2. SELECT * ... ORDER BY id DESC   → ambil riwayat (terbaru di atas)
  3. SELECT COUNT(*), SUM(...)      → hitung total pesanan & omzet
                               │
  4. render HTML              ▼  → kirim ke browser
```

### 5.2 Alur saat form diisi & dikirim (POST)

```
Browser ──POST /index.php──► PHP
                               │
 ① Ambil & bersihkan input      [index.php baris 14–16]
      $nama   = trim($_POST['nama'] ?? '')
      $kelas  = $_POST['tiket'] ?? ''
      $jumlah = (int) ($_POST['jumlah'] ?? 0)
                               │
 ② Validasi di server          [baris 19–24]
      nama kosong?     → "Nama pemesan wajib diisi."
      kelas tak dikenal? → "Silakan pilih tiket."
      jumlah < 1 atau > 10? → "Jumlah tiket harus 1 sampai 10."
      ── gagal → tampilkan pesan error, form diisi ulang
                               │
 ③ Hitung harga (di server)    [baris 27–28]
      $hargaSatuan = $harga[$kelas]      ← harga TIDAK dari input user
      $total       = $hargaSatuan * $jumlah
                               │
 ④ Simpan (Prepared Statement) [baris 34–45]
      INSERT INTO tb_pemesanan (...) VALUES (:nama, :kelas, ...)
                               │
 ⑤ Redirect                    [baris 53–55]
      header('Location: index.php'); exit;
      ← pola PRG (Post/Redirect/Get)
                               │
 ⑥ Tampilkan riwayat & statistik baru
```

**Kenapa pakai redirect (PRG)?** Supaya kalau pengguna menekan tombol *refresh*
setelah memesan, pesanan **tidak tersimpan dua kali**. Setelah menyimpan, server
mengirim perintah redirect, lalu browser meminta ulang halaman dengan method GET
(halaman bersih).

### 5.3 Hitung total secara langsung (di browser)

```
User pilih kelas / ubah jumlah
   → event 'change' / 'input'        [script.js]
   → harga  = HARGA[kelas]           ← dikirim dari PHP (sumber tunggal)
   → jumlah = parseInt(jumlah) || 0
   → $('#total').text('Rp ' + (harga * jumlah).toLocaleString('id-ID'))
```

Total yang tampil di browser **hanya untuk tampilan**. Yang benar-benar
disimpan ke database tetap dihitung ulang oleh server (langkah ③).

### 5.4 Layout Bento

```
┌────────────────────────────────────────────┐
│  HEADER (gradien ungu -> biru)             │
├──────────────────────┬─────────────────────┤
│                      │  TOTAL   │  OMZET   │
│   FORM PEMESANAN     ├─────────────────────┤
│   (kotak besar)      │  PILIHAN TIKET      │
│                      │  Silver  (perak)   │
│                      │  Gold    (emas)     │
│                      │  Diamond (biru)    │
├──────────────────────┴─────────────────────┤
│  RIWAYAT PEMESANAN (penuh lebar)           │
└────────────────────────────────────────────┘
```

Layout memakai grid Bootstrap:

| Kelas CSS | Fungsi |
|---|---|
| `row` / `col-lg-7` / `col-lg-5` / `col-12` | Pembagian kolom |
| `g-3` | Jarak antar kotak |
| `h-100` | Kartu menyamar tinggi dengan kolomnya |
| `d-flex flex-column` + `flex-grow-1` | Kotak merekah mengisi sisa ruang |
| `badge badge-{kelas}` | Warna badge mengikuti kelas tiket |

---

## 6. Database

### 6.1 Tabel `tb_pemesanan`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INTEGER PRIMARY KEY AUTOINCREMENT | Primary key, auto naik |
| `nama_pemesan` | VARCHAR(100) NOT NULL | Nama pemesan |
| `kelas_tiket` | VARCHAR(10) NOT NULL | silver / gold / diamond |
| `jumlah_tiket` | INTEGER NOT NULL | 1 sampai 10 |
| `harga_satuan` | INTEGER NOT NULL | Harga per tiket saat transaksi |
| `total_harga` | INTEGER NOT NULL | `harga_satuan × jumlah_tiket` |
| `created_at` | TEXT DEFAULT CURRENT_TIMESTAMP | Waktu otomatis |

**Data awal (4 pesanan):**

| Nama | Kelas | Jumlah | Total |
|---|---|---|---|
| Budi Santoso | Silver | 2 | Rp 20.000 |
| Siti Rahma | Gold | 4 | Rp 120.000 |
| Agus Salim | Diamond | 1 | Rp 60.000 |
| Dewi Anggraini | Gold | 3 | Rp 90.000 |

Total omzet: **Rp 290.000**

**Kenapa `harga_satuan` disimpan terpisah dari `total_harga`?**
Supaya histori tidak berubah. Kalau nanti harga Silver dinaikkan ke Rp 12.000,
pesanan lama harus tetap tercatat pada harga Rp 10.000 yang pernah dibayar.

### 6.2 Apakah data hilang kalau website dibuka lagi?

**Tidak.** Sudah diuji: membuka halaman 8x berturut-turut → data tetap
`4 pesanan, omzet Rp 290.000`.

Penyebabnya ada di `config.php`:

```php
// dicek apakah tabel sudah pernah dibuat
$tabelAda = $koneksi->query(
    "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'tb_pemesanan'"
)->fetchColumn();

// HANYA jalankan tiket.sql kalau tabelnya belum ada
if (!$tabelAda) {
    $koneksi->exec(file_get_contents(__DIR__ . '/tiket.sql'));
}
```

`tiket.sql` (berisi `CREATE TABLE` + data contoh) **hanya dijalankan satu kali
saja**, yaitu saat tabelnya belum pernah dibuat. Setelah itu tidak pernah lagi.

| Aksi | Efek ke database |
|---|---|
| Membuka website | Hanya **membaca** (SELECT) — tidak ada yang berubah |
| Mengisi form + klik "Pesan Tiket" | 1 baris baru ditambahkan |
| Menghapus `database/ticket-acara.sqlite` | Tabel + data contoh dibuat ulang (reset) |

> `tiket.sql` sengaja **tidak** memakai `DROP TABLE`, supaya data tidak pernah
> terhapus karena kesalahan.

### 6.3 Melihat & mengubah data

```bash
# Lihat isi database
sqlite3 database/ticket-acara.sqlite "SELECT * FROM tb_pemesanan;"

# Lihat ringkas
sqlite3 database/ticket-acara.sqlite "SELECT COUNT(*), SUM(total_harga) FROM tb_pemesanan;"

# Reset ke data awal
rm database/ticket-acara.sqlite    # lalu buka halamannya sekali
```

---

## 7. Keamanan

| Praktik | Status | Lokasi |
|---|---|---|
| Prepared statement (anti SQL Injection) | ✅ | `index.php` |
| `htmlspecialchars()` (anti XSS) | ✅ | `index.php` |
| Validasi input di server | ✅ | `index.php` |
| Whitelist kelas tiket (`isset($harga[...])`) | ✅ | `index.php` |
| Harga ditentukan server, bukan dari input user | ✅ | `index.php` |
| Type casting `(int)` | ✅ | `index.php` |
| File database tidak bisa diunduh via web | ✅ | `router.php` + `.htaccess` |
| CSRF token | ❌ belum ada | — |
| Login / session | ❌ belum ada | — |
| Pagination | ❌ belum ada | — |

### Kenapa prepared statement penting?

Query biasa menempelkan nilai user langsung ke teks SQL:

```php
// BERBAHAYA
$sql = "INSERT INTO tb_pemesanan (nama_pemesan) VALUES ('$nama')";
```

Kalau user mengetik `' OR '1'='1`, query-nya berubah dan bisa merusak database.

Prepared statement memisahkan query dari nilainya:

```php
// AMAN
$stmt = $koneksi->prepare(
    "INSERT INTO tb_pemesanan (nama_pemesan, ...) VALUES (:nama, ...)"
);
$stmt->execute([':nama' => $nama, ...]);
```

Nilai `Budi' OR '1'='1` akan tersimpan **sebagai teks biasa**, bukan jadi
perintah SQL.

### Kenapa validasi berlapis?

| Lapis | Peran | Bisa dilewati? |
|---|---|---|
| HTML5 (`min`, `max`, `type`) | Memberi petunjuk ke browser | Ya (dikirim via Postman) |
| JavaScript (`script.js`) | Kenyamanan user | **Ya** (matikan JS) |
| **PHP (`index.php`)** | **Batas keamanan yang nyata** | **Tidak** |

---

## 8. Troubleshooting

| Masalah | Penyebab & Solusi |
|---|---|
| `could not find driver` | Ekstensi SQLite belum aktif → `sudo sed -i 's/^;extension=pdo_sqlite/extension=pdo_sqlite/' /etc/php/php.ini` lalu `sudo systemctl restart php-fpm` |
| `Koneksi database gagal` | Folder `database/` tidak bisa ditulis → `sudo chmod 777 database/` |
| `Gagal menyimpan pesanan...` | File database read-only → `sudo chmod 666 database/ticket-acara.sqlite` |
| `Address already in use` | Port 8000 dipakai → pakai port lain: `php -S localhost:8080 -t . router.php` |
| Perubahan kode tidak muncul | OPcache menahan 3 menit → tambah `-d opcache.revalidate_freq=0` |
| Halaman PHP menampilkan kode | `router.php` belum dipakai → pastikan perintah memuat `router.php` |
| Data hilang / salah | `rm database/ticket-acara.sqlite` lalu buka halamannya lagi |

**Perintah bantu:**

```bash
php -l index.php                                          # cek syntax PHP
node --check script.js                                    # cek syntax JS
sqlite3 database/ticket-acara.sqlite ".tables"            # lihat daftar tabel
```