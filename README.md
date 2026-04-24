# Tukubuku - Toko Buku Online Modern

Tukubuku adalah platform e-commerce toko buku online yang dibangun dengan **Laravel 11**, dirancang dengan fokus pada performa, reliabilitas pembayaran, dan pengalaman pengguna yang responsif (*real-time*).

## 🚀 Fitur Unggulan

- **Integrasi Pembayaran Midtrans**: Mendukung berbagai metode pembayaran (VA, QRIS, GoPay, Gerai Retail).
- **Sinkronisasi Real-time**: Update status pembayaran secara instan tanpa refresh halaman menggunakan **Laravel Reverb (Websockets)**.
- **Reliabilitas Status**: Sistem otomatis (*Background Job*) untuk memastikan status pesanan tetap sinkron dengan Midtrans meskipun webhook gagal.
- **Modern Build System**: Menggunakan **Vite** dan **Tailwind CSS v4** untuk performa frontend yang optimal.
- **Dashboard Admin**: Manajemen inventaris buku, pemantauan transaksi, dan ekspor data laporan.

## 🛠 Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL & Redis
- **Real-time**: Laravel Reverb
- **Frontend**: Tailwind CSS v4, Vanilla JS, Laravel Echo
- **Deployment**: Docker Support

## 📦 Instalasi & Setup

### 1. Persiapan Awal
```bash
git clone https://github.com/marifyahya/tukubuku.git
cd tukubuku
composer install
npm install
cp .env.example .env
```

### 2. Konfigurasi Environment
Sesuaikan kredensial berikut di file `.env`:
- **Database**: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- **Midtrans**: `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`
- **Broadcasting**: `BROADCAST_CONNECTION=reverb`
- **Queue**: `QUEUE_CONNECTION=redis`

### 3. Inisialisasi Aplikasi
```bash
php artisan key:generate
php artisan migrate --seed
npm run build
```

## 🚥 Menjalankan Aplikasi

Untuk menjalankan fitur lengkap (termasuk fitur real-time), Anda perlu menjalankan proses berikut secara bersamaan:

### A. Web Server
```bash
php artisan serve
```

### B. Websocket Server (Reverb)
Diperlukan untuk fitur update status pembayaran otomatis di browser user.
```bash
php artisan reverb:start
```

### C. Queue Worker
Diperlukan untuk memproses pengiriman notifikasi/broadcast di latar belakang.
```bash
php artisan queue:work
```

### D. Frontend Assets (Development)
```bash
npm run dev
```

## 🐳 Menggunakan Docker
Jika Anda lebih suka menggunakan Docker, jalankan:
```bash
docker-compose up -d --build
docker exec -it tukubuku_php php artisan migrate --seed
```

## 📊 Fitur Admin & Keamanan
- **Audit Trail**: Setiap perubahan status pembayaran terekam lengkap dalam `order_payment_histories`.
- **Manual Sync**: Admin memiliki tombol khusus untuk menarik status terbaru langsung dari API Midtrans jika terjadi *desync*.

## 📄 Lisensi
Proyek ini dilisensikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
