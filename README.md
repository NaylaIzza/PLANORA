```
planora/
│
├── index.html
│
├── app/
│   ├── login.html
│   ├── register.html
│   ├── workspace.html
│   ├── profil.html
│   └── landing.html
│
├── api/
│   ├── auth.php
│   ├── tasks.php
│   ├── user.php
│   ├── config/
│   │   └── database.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── TaskController.php
│   │   └── UserController.php
│   └── helpers/
│       └── response.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   └── img/
│       ├── Lg.png
│       ├── logo.png
│       ├── bg1.png
│       └── bg2.png
│
└── database/
    └── db_planora.sql
```
|---------|--------|
| "Tidak bisa terhubung ke server" | Pastikan Apache & MySQL XAMPP sudah **Start** |
| "Koneksi database gagal" | Cek `api/config/database.php` |
| Database tidak ada | Jalankan ulang `database/db_planora.sql` di phpMyAdmin |
| Halaman tidak terbuka | Pastikan folder ada di `C:\xampp\htdocs\planora\` |
