# 🧪 LATIHAN UK

## 🎯 Tema

**Sistem Informasi Pemesanan Tiket Event Berbasis Web**

**Teknologi:** PHP Native + MySQL + Bootstrap

------------------------------------------------------------------------

## 🔹 BAGIAN A: Persiapan Database

### Tugas 1

Buat database dengan nama: event_tiket

### Tugas 2

Buat tabel sesuai ERD: - users - venue - event - tiket - orders -
order_detail - voucher - attendee

### Tugas 3

Tentukan: - Primary Key tiap tabel - Foreign Key sesuai relasi pada ERD

📌 Output: Script SQL lengkap (CREATE TABLE + relasi)

------------------------------------------------------------------------

## 🔹 BAGIAN B: Sistem Login

### Tugas 4

-   Input: email & password
-   Role: admin dan user
-   Redirect:
    -   admin → dashboard admin
    -   user → dashboard user

### Tugas 5

Buat fitur logout menggunakan session

📌 Output: - Halaman login berfungsi - Session login aktif

------------------------------------------------------------------------

## 🔹 BAGIAN C: CRUD Master Data (Admin)

### Venue

-   Tambah, Edit, Hapus, Tampil

### Event

-   Relasi dengan venue
-   Input tanggal

### Tiket

-   Relasi ke event
-   Harga & kuota

### Voucher

-   Kode, potongan, status

------------------------------------------------------------------------

## 🔹 BAGIAN D: Pemesanan Tiket

-   Pilih tiket
-   Input qty
-   Hitung subtotal
-   Simpan ke orders & order_detail

------------------------------------------------------------------------

## 🔹 BAGIAN E: Voucher & Pembayaran

-   Input voucher
-   Validasi
-   Hitung diskon
-   Update total & status

------------------------------------------------------------------------

## 🔹 BAGIAN F: Generate Tiket

-   Generate kode unik
-   Simpan ke attendee

------------------------------------------------------------------------

## 🔹 BAGIAN G: Check-in

-   Input kode tiket
-   Update status & waktu

------------------------------------------------------------------------

## 🔹 BAGIAN H: Dashboard

-   Total user
-   Total order
-   Total pendapatan

------------------------------------------------------------------------

## 🔹 BAGIAN I: UI

-   Bootstrap
-   Responsive
-   Card & tabel

------------------------------------------------------------------------

## 🔹 BAGIAN J: HOTS

-   Cegah over kuota
-   Query tiket terjual
-   Riwayat user
-   Analisis voucher

------------------------------------------------------------------------

## 🎯 Output Akhir

-   Aplikasi web
-   Database sesuai ERD
-   Sistem berjalan
