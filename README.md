# Tukubuku - Toko Buku Online

Tukubuku adalah aplikasi e-commerce sederhana berbasis Laravel untuk menjual buku secara online.

## Prasyarat (Prerequisites)

Sebelum menjalankan proyek ini, pastikan Anda telah menginstal:

- [PHP >= 8.2](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- [Docker](https://www.docker.com/) (opsional jika menggunakan Docker)
- Database (MySQL/SQLite)

## Cara Menjalankan di Lokal (Tanpa Docker)

1. **Clone repository ini:**
   ```bash
   git clone https://github.com/marifyahya/bookstore.git
   cd bookstore
   ```

2. **Instal dependensi PHP:**
   ```bash
   composer install
   ```

3. **Instal dependensi JavaScript dan build aset:**
   ```bash
   npm install
   npm run build
   ```

4. **Siapkan file konfigurasi `.env`:**
   ```bash
   cp .env.example .env
   ```
   *Buka file `.env` dan sesuaikan pengaturan database Anda.*

5. **Generate kunci aplikasi:**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan migrasi database dan seeder:**
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan server pengembangan:**
   ```bash
   php artisan serve
   ```
   *Buka `http://localhost:8000` di browser Anda.*

## Cara Menjalankan Menggunakan Docker

1. **Siapkan file `.env`:**
   ```bash
   cp .env.example .env
   ```
   Pastikan pengaturan database di `.env` sesuai dengan yang ada di `docker-compose.yml`.

2. **Build dan jalankan kontainer:**
   ```bash
   docker-compose up -d --build
   ```

3. **Jalankan perintah Laravel di dalam kontainer:**
   ```bash
   docker exec -it tukubuku_php composer install
   docker exec -it tukubuku_php php artisan key:generate
   docker exec -it tukubuku_php php artisan migrate --seed
   ```

4. **Instal & Build Aset (Lokal):**
   ```bash
   npm install && npm run build
   ```

Akses aplikasi di `http://localhost:8000`.

## Fitur Utama

- **User:** Browsing buku, Keranjang belanja, Checkout pesanan.
- **Admin:** Manajemen buku, Manajemen pengguna, Manajemen pesanan, Dashboard statistik.

## Lisensi

Proyek ini adalah perangkat lunak open-source yang dilisensikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
