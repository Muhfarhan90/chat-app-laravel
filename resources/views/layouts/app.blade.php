<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>

<script type="module">
    const myId = {{ auth()->id() }};
    // Pastikan path ini benar. Coba buka http://127.0.0.1:8000/sounds/notification.mp3 di tab baru untuk cek
    const notificationSound = new Audio('/sounds/notification.mp3');

    // Minta izin notifikasi segera setelah dashboard terbuka
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    Echo.private('user.' + myId)
        .listen('.message.sent', (e) => {
            console.log("-------------------------------");
            console.log("PESAN DITERIMA DI DASHBOARD!");
            console.log("Pengirim:", e.message.sender_id);
            console.log("Pesan:", e.message.message);

            // 1. UPDATE BADGE (Jika ada)
            const badge = document.getElementById('unread-count-' + e.message.sender_id);
            if (badge) {
                let currentCount = parseInt(badge.innerText) || 0;
                badge.innerText = currentCount + 1;
                badge.classList.remove('hidden');
            }

            // 2. COBA MAINKAN SUARA
            // Kita gunakan Promise untuk menangkap error jika diblokir browser
            let playPromise = notificationSound.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {
                        console.log("✅ Audio berhasil diputar.");
                    })
                    .catch(error => {
                        console.error("❌ Audio DIBLOKIR Browser.");
                        console.error("Alasan: User belum interaksi (klik) di halaman ini.");
                        console.error("Solusi: Klik sembarang tempat di dashboard setidaknya sekali.");
                    });
            }

            // 3. NOTIFIKASI POPUP
            // Cek URL agar tidak notif double jika sedang chat dengan orangnya
            if (!window.location.href.includes('/chat/' + e.message.sender_id)) {

                if (Notification.permission === "granted") {
                    new Notification("Pesan Baru dari " + e.message.sender_id, {
                        body: e.message.message,
                        icon: '/favicon.ico',
                        silent: true // Biar tidak bentrok dengan suara notification.mp3 kita
                    });
                } else {
                    console.warn("Izin notifikasi belum diberikan di browser ini.");
                }
            }
        });
</script>
