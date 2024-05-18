# Toko Kosmetik Keybeauty

Aplikasi perpustakaan untuk Toko Kosmetik Keybeauty yang dibangun menggunakan Laravel.

## Persiapan Sebelum Menggunakan

1. **Pastikan Anda telah menginstal:**
   - PHP >= 7.3
   - Composer
   - Visual Studio Code
   - Visual Studio Code Extensions

2. **Clone repository ini:**
   ```bash
   git clone https://github.com/arbiet/tokokosmetik-keybeauty.git
   cd tokokosmetik-keybeauty
   ```

3. **Instal Laragon jika belum terinstal:**
    Unduh Laragon dari laragon.org/download dan ikuti langkah-langkah instalasi yang disediakan. https://laragon.org/download/

4. **Instal Composer jika belum terinstal:**
    Unduh Composer dari getcomposer.org/download dan ikuti petunjuk instalasi yang tersedia untuk sistem operasi Anda.
    https://getcomposer.org/download/

5. **Instal Nodejs Binaries jika belum terinstal:**
    Unduh Nodejs Binaries dari nodejs.org/en/download dan ikuti petunjuk instalasi yang tersedia untuk sistem operasi Anda.
    https://nodejs.org/en/download/prebuilt-installer

6. **Menyalakan ekstensi PHP di Laragon:**
    - Buka Laragon, pilih menu "PHP" > "Extension", kemudian centang pdo_sqlite dan sqlite3.
    - Jika ekstensi-ekstensi tersebut tidak ada dalam daftar, kamu mungkin perlu menambahkan atau mengaktifkannya melalui menu "PHP" > "Quick add" atau dengan mengedit file php.ini.

7. **Instal Visual Studio Code jika belum terinstal:**
    Unduh Visual Studio Code dari code.visualstudio.com/download dan ikuti langkah-langkah instalasi yang disediakan. https://code.visualstudio.com/Download

8. **Instal Ekstensi Visual Studio Code yang Diperlukan:**
    Setelah menginstal Visual Studio Code, buka VS Code, dan instal ekstensi berikut:
    - PHP Namespace Resolver
    - Laravel Snippets
    - Laravel Blade Snippets
    - Laravel Blade Formatter
    - Laravel Blade Spacer
    - Laravel Extra Intellisense
    - Laravel GoTo View
    - Tailwind CSS Intellisense
    - Alpine.js IntelliSense
    - Alpine.js Syntax Highlight

## Instalasi Laravel

1. **Instal dependensi menggunakan Composer:**
    ```bash
    composer install
    ```

2. **Buat file .env dari .env.example dan sesuaikan konfigurasi:**
    ```bash
    cp .env.example .env
    ```

3. **Generate application key:**
    ```bash
    php artisan key:generate
    ```

4. **Konfigurasikan database di file .env:**
    ```bash
    DB_CONNECTION=sqlite
    ```

5. **Migrasi dan seed database:**
    ```bash
    php artisan migrate --seed
    ```

## Menyalakan Aplikasi
1. **Jalankan server pengembangan Laravel:**
    ```bash
    php artisan serve
    ```

2. **Akses aplikasi di browser:**
    ```bash
    http://localhost:8000
    ```

## Kontribusi
- @ikimukti
- @aprilbela

## Lisensi
Proyek ini dilisensikan di bawah MIT License. Ini memberikan panduan lengkap dari persiapan, instalasi, hingga menjalankan aplikasi Laravel Anda.


