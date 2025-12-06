# 💬 Laravel 12 Real-Time Chat (Powered by Reverb)

Aplikasi chatting real-time modern yang dibangun menggunakan **Laravel 12** dan **Laravel Reverb**. Proyek ini mendemonstrasikan implementasi WebSocket _first-party_ dari Laravel tanpa bergantung pada layanan pihak ketiga (seperti Pusher), serta menangani fitur UX kompleks seperti notifikasi suara dan kebijakan autoplay browser.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![Reverb](https://img.shields.io/badge/Reverb-WebSocket-orange)

## 📸 Screenshots
<img width="950" height="870" alt="image" src="https://github.com/user-attachments/assets/a3dc3d29-9c4b-46f4-b8c0-1704632ed198" />
*Tampilan Dashboard dengan Unread Counter Real-time*

<img width="1912" height="896" alt="image" src="https://github.com/user-attachments/assets/d52fc907-5286-4194-9502-e16b8fcde9ca" />
*Tampilan Private Chat Room*

## ✨ Fitur Utama

-   **⚡ Real-Time Messaging**: Pesan terkirim dan diterima secara instan menggunakan Laravel Reverb (WebSocket).
-   **🔒 Private Chat**: Sistem chat 1-on-1 yang aman antar pengguna.
-   **🔔 Global Notification System**:
    -   **Sound Alert**: Notifikasi suara "Ting" saat pesan masuk (bahkan saat di dashboard).
    -   **Browser Push Notification**: Pop-up notifikasi sistem saat tab tidak aktif.
-   **🔴 Live Unread Counter**: Badge angka pesan belum terbaca yang update secara real-time di dashboard tanpa refresh.
-   **🧠 Smart Audio Handling**: Implementasi UX "Click Trap" untuk menangani *Browser Autoplay Policy* (memastikan suara notifikasi tetap jalan di Chrome/Brave/Edge).
-   **📱 Responsive UI**: Dibangun dengan Tailwind CSS dan Laravel Breeze.

## 🛠️ Teknologi yang Digunakan

-   **Backend**: Laravel 12
-   **WebSocket Server**: Laravel Reverb (Self-hosted)
-   **Frontend**: Blade Templates, Tailwind CSS
-   **Client-side WebSocket**: Laravel Echo & Pusher-JS
-   **Database**: MySQL

## 🚀 Instalasi & Cara Menjalankan

Ikuti langkah-langkah ini untuk menjalankan proyek di komputer lokal:

### 1. Clone Repository
```bash
git clone [https://github.com/username-anda/nama-repo-anda.git](https://github.com/username-anda/nama-repo-anda.git)
cd nama-repo-anda

### 2. Install Dependencies
Install paket PHP dan Node.js yang dibutuhkan.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
