# 🛒 E-Commerce Fashion — Laravel 12

**Ujian Praktikum Pemrograman Web (Fullstack E-Commerce + RBAC + Wallet & Virtual Account)**

## 👥 Anggota Kelompok

| Nama                      | NIM                 | Jobdesk                                                      |
| ------------------------- | ------------------- | ------------------------------------------------------------ |
| **Shananda Putri Aisyah** | **245150601111013** | Seller Page, CRUD Produk & Kategori                          |
| **Silvia Eka Putri**      | **245150607111014** | Checkout, Wallet & Virtual Account, Payment Page, Admin Page |

---

# 📌 Deskripsi Proyek

Aplikasi **E-Commerce Fashion** berbasis **Laravel 12** yang menyediakan fitur jual-beli produk fashion (contoh: pakaian, sepatu, aksesoris), lengkap dengan sistem toko/penjual, transaksi, dompet digital (*wallet*), dan pembayaran menggunakan **Virtual Account (VA)**.

Aplikasi dibangun dengan konsep **fullstack**: dari tampilan pengguna, proses CRUD, validasi, hingga alur transaksi & pembayaran.

---

# 🏗️ Teknologi yang Digunakan

* **Laravel 12**
* **PHP 8+**
* **MySQL/MariaDB**
* **Laravel Breeze** (autentikasi)
* **TailwindCSS + Vite**
* **Node.js & NPM**

---

# 🚀 Fitur Utama Aplikasi

## 🔐 1) RBAC (Role Based Access Control)

Terdapat 3 peran utama:

* **Admin**
* **Member (Customer)**
* **Seller (Member yang memiliki Store)**

Akses halaman dibatasi melalui middleware (role & isSeller).

---

## 🛒 2) Customer (Member)

Fitur yang disediakan:

* **Homepage produk** + filter kategori
* **Detail produk** (gambar, toko, ulasan jika ada)
* **Checkout** + pilih metode pembayaran (**Wallet / VA Transfer**)
* **Riwayat transaksi**
* **Topup saldo** via **VA** (wallet)

---

## 🏪 3) Seller Dashboard (Toko Fashion)

Hanya bisa diakses oleh **member yang sudah membuat store**.
Fitur:

* Registrasi & manajemen toko
* CRUD kategori produk
* CRUD produk + multiple gambar + thumbnail
* Profil seller/toko

---

## 🛡️ 4) Admin

Fitur:

* Verifikasi toko (approve/reject)
* Manajemen user & store

---

# 💳 Sistem Pembayaran (Sesuai Challenge)

## A. Wallet / Saldo (Topup via VA)

**Flow:**

1. Member mengajukan topup
2. Sistem membuat **Virtual Account unik**
3. Pembayaran dilakukan via **Payment Page**
4. Jika valid → **saldo (user_balances) bertambah**

✅ Sesuai challenge “User Wallet & VA”.

---

## B. VA untuk Pembelian (Checkout)

**Flow:**

1. Checkout → sistem membuat transaksi
2. Sistem generate **VA unik terkait transaction_id**
3. Pembayaran dilakukan via **Payment Page**
4. Jika valid → `transactions.payment_status = paid`
   lalu dana masuk ke saldo toko (**store_balances**)

✅ Sesuai challenge “VA Pembelian Langsung”.

---

## ✅ Dedicated Payment Page (Terpusat)

Satu halaman khusus untuk memproses:

* **Topup VA**
* **Pembelian VA**

**Flow sesuai soal:**

1. Input kode VA
2. Sistem tampilkan tagihan
3. Input nominal transfer (simulasi)
4. Validasi nominal
5. Jika sukses:

   * Topup → tambah saldo user
   * Pembelian → transaksi paid + tambah saldo toko

✅ Ini memenuhi challenge “Halaman Pembayaran Terpusat”.

---

# 🗄️ Database Tambahan (Sesuai Soal)

Aplikasi menambahkan tabel:

* `user_balances` → saldo wallet user
* `virtual_accounts` → data VA untuk topup & transaksi

✅ Sesuai requirement “wajib membuat tabel baru”.

---

# 🌱 Seeder (Sesuai Minimal Requirement)

Seeder menyediakan data awal:

* **1 Admin**
* **2 Member**
* **1 Store** (milik salah satu member)
* **5 Kategori Fashion**
* **≥10 Produk Fashion** 

✅ Memenuhi target minimal seeder.

---

# ⚙️ Langkah Instalasi

1. `git clone <repo>`
2. `composer install`
3. `npm install`
4. `cp .env.example .env` lalu set DB
5. `php artisan key:generate`
6. `php artisan migrate`
7. `php artisan db:seed`
8. `php artisan serve`
9. `npm run dev`

---

# 📂 Struktur Folder

```
app/
database/
public/
resources/
routes/
storage/
vendor/
README.md
```

---

# 🧑‍💻 Catatan Teknis Developer

* Transaksi finansial memakai `DB::transaction()` agar aman (atomic).
* Middleware:

  * `role` untuk admin/member
  * `isSeller` untuk seller (member + punya store)
* Penerapan clean code: pemisahan logika melalui **Service Class** untuk proses payment/wallet.




