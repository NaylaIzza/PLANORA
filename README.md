# PLANORA — Panduan Instalasi XAMPP

## Struktur Folder

```
planora/                        ← Taruh di C:\xampp\htdocs\planora\
│
├── index.html                  ← Landing page (entry point utama)
│
├── app/                        ← Semua halaman HTML aplikasi
│   ├── login.html
│   ├── register.html
│   ├── workspace.html
│   ├── profil.html
│   └── landing.html
│
├── api/                        ← Backend PHP
│   ├── auth.php                ← Endpoint: register & login
│   ├── tasks.php               ← Endpoint: CRUD tugas
│   ├── user.php                ← Endpoint: update profil
│   ├── config/
│   │   └── database.php        ← Konfigurasi koneksi MySQL
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── TaskController.php
│   │   └── UserController.php
│   └── helpers/
│       └── response.php        ← Helper JSON response
│
├── assets/
│   ├── css/
│   │   └── style.css           ← Global stylesheet
│   ├── js/
│   │   └── app.js              ← Global JS utilities
│   └── img/
│       ├── Lg.png
│       ├── logo.png
│       ├── bg1.png
│       └── bg2.png
│
└── database/
    └── db_planora.sql          ← Script SQL database
```

---

## Langkah Instalasi

### 1. Copy ke htdocs
Salin seluruh folder `planora` ke:
```
C:\xampp\htdocs\planora\
```

### 2. Jalankan XAMPP
- Buka **XAMPP Control Panel**
- Klik **Start** pada **Apache** dan **MySQL**

### 3. Import Database
- Buka browser → `http://localhost/phpmyadmin`
- Klik tab **SQL**
- Copy-paste isi file `database/db_planora.sql` → klik **Go**

### 4. (Opsional) Sesuaikan Konfigurasi
Buka `api/config/database.php` jika perlu ubah kredensial:
```php
define('DB_USER', 'root');   // username MySQL
define('DB_PASS', '');       // password MySQL (default XAMPP: kosong)
```

### 5. Buka Aplikasi
```
http://localhost/planora/
```

---

## Peta Endpoint API

| Endpoint              | Method | Aksi            |
|-----------------------|--------|-----------------|
| `api/auth.php?action=register` | POST | Daftar akun baru |
| `api/auth.php?action=login`    | POST | Login           |
| `api/tasks.php?action=list`    | GET  | Ambil tugas     |
| `api/tasks.php?action=tambah`  | POST | Tambah tugas    |
| `api/tasks.php?action=selesai` | POST | Selesaikan tugas|
| `api/user.php`                 | POST | Update profil   |

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| "Tidak bisa terhubung ke server" | Pastikan Apache & MySQL XAMPP sudah **Start** |
| "Koneksi database gagal" | Cek `api/config/database.php` |
| Database tidak ada | Jalankan ulang `database/db_planora.sql` di phpMyAdmin |
| Halaman tidak terbuka | Pastikan folder ada di `C:\xampp\htdocs\planora\` |
