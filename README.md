# 🛒 SHOP.CO — E-Commerce Fashion (Laravel 12)
**Ujian Praktikum Pemrograman Web | Fullstack E-Commerce + RBAC + Wallet & Virtual Account**

SHOP.CO adalah aplikasi **E-Commerce Fashion** berbasis **Laravel 12** yang menyediakan fitur belanja produk, keranjang, checkout, riwayat transaksi, serta sistem pembayaran menggunakan **E-Wallet (Saldo)** dan **Virtual Account (VA)** dengan **halaman Payment terpusat**. Aplikasi menerapkan **RBAC** untuk membatasi akses berdasarkan role pengguna.

---

## 👥 Anggota Kelompok

| Nama | NIM | Kontribusi |
|---|---|---|
| **Shananda Putri Aisyah** | **245150601111013** | Seller Dashboard, CRUD Kategori & Produk |
| **Silvia Eka Putri** | **245150607111014** | Checkout, Wallet & VA, Payment Page, Admin Panel |

---

## 🧩 Teknologi
- Laravel 12 + Laravel Breeze (Auth)
- PHP 8+
- MySQL/MariaDB
- Blade + TailwindCSS + Vite
- Node.js & NPM

---

## 🔐 RBAC (Role Based Access Control)

### Role: Admin (`role:admin`)
Akses ke:
- Admin Dashboard
- Verifikasi Toko (Approve/Reject)
- Manajemen User & Store (update role, delete user)

### Role: Member/Customer (`role:member`)
Akses ke:
- Customer Dashboard
- Cart, Checkout, History
- Wallet (Topup & VA)
- Store Register (mendaftar toko)

### Role: Seller (`middleware:seller`)
Seller adalah **member yang memiliki store** (terdaftar di tabel `stores`) dan hanya bisa akses:
- Seller Dashboard
- CRUD Kategori
- CRUD Produk
- Manajemen Order (lihat & update status)

---

## 🧭 Daftar Halaman & Fitur (Sesuai Route)

### 🌐 Public
- `GET /` → Homepage (list produk + kategori + new arrivals + top selling)
- `GET /categories` → List kategori
- `GET /category/{id}` → Produk berdasarkan kategori
- `GET /product/{slug}` → Detail produk
- `GET /products/search?q=...` → Live search produk (AJAX)

### 👤 Auth & Profile (Breeze)
- Login/Register/Forgot/Reset/Verify Email (dari Breeze)
- `GET /profile` → Edit profile
- `PATCH /profile` → Update profile
- `DELETE /profile` → Delete account

### 🧑 Customer (Member)
- `GET /customer/dashboard` → Customer dashboard
- `GET /cart` → Keranjang
- `POST /cart/add/{product}` → Add to cart
- `PATCH /cart/{cart}` → Update quantity cart
- `DELETE /cart/{cart}` → Delete item cart
- `GET /checkout` → Form checkout (alamat + pilihan pembayaran)
- `POST /checkout` → Proses checkout (buat transaction + details)
- `GET /history` → Riwayat transaksi (selesai & dibatalkan)
- `GET /transactions` → List transaksi

### 🏪 Store Register (Member)
- `GET /store/register` → Form pendaftaran toko
- `POST /store/register` → Simpan data store

### 💰 Wallet & Payment
- `GET /wallet` → Halaman saldo / wallet
- `GET /wallet/topup` → Form topup (generate VA)
- `POST /wallet/topup` → Buat request topup + VA
- `GET /payment` → Halaman payment terpusat (input VA)
- `POST /payment/confirm` → Konfirmasi pembayaran VA (simulasi)

### 🧑‍💼 Seller (Middleware: `seller`)
- `GET /seller/dashboard` → Seller dashboard
- **Kategori**
  - `GET /seller/categories` → List kategori
  - `GET /seller/categories/create` → Create
  - `POST /seller/categories` → Store
  - `GET /seller/categories/{category}/edit` → Edit
  - `PUT /seller/categories/{category}` → Update
  - `DELETE /seller/categories/{category}` → Delete
- **Produk**
  - `GET /seller/products` → List produk
  - `GET /seller/products/create` → Create
  - `POST /seller/products` → Store
  - `GET /seller/products/{product}/edit` → Edit
  - `PUT /seller/products/{product}` → Update
  - `DELETE /seller/products/{product}` → Delete
- **Orders**
  - `GET /seller/orders` → List order masuk
  - `GET /seller/orders/{transaction}` → Detail order
  - `PATCH /seller/orders/{transaction}/status` → Update status order

### 🛡️ Admin (Middleware: `role:admin`)
- `GET /admin/dashboard` → Admin dashboard (ringkasan)
- **Verifikasi Toko**
  - `GET /admin/verification` → List pending stores
  - `POST /admin/verification/{store}/approve` → Approve store
  - `POST /admin/verification/{store}/reject` → Reject store
- **Manajemen User & Store**
  - `GET /admin/users` → List user + store info
  - `PATCH /admin/users/{user}/role` → Update role user
  - `DELETE /admin/users/{user}` → Delete user

---

## 💳 Sistem Pembayaran (Challenge)

### A) Wallet / Saldo (Topup via VA)
Flow:
1. User membuka `GET /wallet/topup`
2. Sistem membuat kode VA unik untuk topup
3. User membayar via halaman `GET /payment`
4. Jika nominal valid → saldo user bertambah

### B) Bayar Langsung (VA untuk transaksi)
Flow:
1. Checkout `POST /checkout` membuat transaksi + VA untuk transaksi
2. User membayar via `GET /payment`
3. Jika valid → transaksi menjadi `paid`

> Konfirmasi pembayaran dilakukan melalui `POST /payment/confirm` (simulasi input VA + nominal).

---

## 🗄️ Database Tambahan
- `user_balances` → menyimpan saldo wallet user
- `virtual_accounts` → menyimpan VA untuk topup & transaksi

---

## 🌱 Seeder
Seeder minimal:
- 1 user role **admin**
- 2 user role **member**
- 1 store milik salah satu member
- 5 kategori
- ≥ 10 produk

---

## ⚙️ Cara Menjalankan
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
npm run dev
