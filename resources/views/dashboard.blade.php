<x-app-layout>
    <x-slot name="header">
        <div id="click-trap" style="display: none;"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm">
                <h3 class="text-lg font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 mb-4">Klik tombol di bawah untuk mengaktifkan notifikasi suara.</p>
                <button onclick="unlockAudio()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Mulai Dashboard 🚀
                </button>
            </div>
        </div>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold mb-4">Pilih Teman untuk Chat:</h3>

                    {{-- Cek apakah ada user lain selain diri sendiri --}}
                    @if ($users->isEmpty())
                        <div class="p-4 bg-yellow-100 text-yellow-700 rounded">
                            <p>Belum ada user lain yang terdaftar.</p>
                            <p class="text-sm mt-2">Tips: Buka browser lain (Incognito), lalu register akun baru untuk
                                mencoba chat.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($users as $user)
                                <a href="{{ route('chat.show', $user->id) }}"
                                    class="block p-6 border border-gray-200 rounded-lg hover:bg-blue-50 transition relative">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 relative">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">Klik untuk chat</p>
                                        </div>

                                        <div id="unread-count-{{ $user->id }}"
                                            class="{{ $user->unread_count > 0 ? '' : 'hidden' }} bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $user->unread_count }}
                                        </div>

                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // PERUBAHAN 2: Cek dulu di LocalStorage
        // Apakah user ini sudah pernah klik sebelumnya?
        const hasPermission = localStorage.getItem('audio_permission_granted');

        // Jika BELUM ada izin, baru kita munculkan modalnya
        if (!hasPermission) {
            document.getElementById('click-trap').style.display = 'flex';
        }
    });

    function unlockAudio() {
        const silentAudio = new Audio('/sounds/notification.mp3');
        silentAudio.volume = 0;

        silentAudio.play().then(() => {
            console.log("Audio Unlocked!");

            // PERUBAHAN 3: Simpan tanda ke LocalStorage
            localStorage.setItem('audio_permission_granted', 'true');

            // Sembunyikan Modal
            document.getElementById('click-trap').style.display = 'none';
        }).catch((err) => {
            console.log("Gagal memutar audio, tapi kita tetap tutup modal:", err);

            // Tetap simpan tanda agar user tidak terganggu terus
            localStorage.setItem('audio_permission_granted', 'true');
            document.getElementById('click-trap').style.display = 'none';
        });
    }

    // TAMBAHAN BARU: FIX UNREAD COUNT CACHE
    // Fungsi ini mendeteksi jika user kembali ke halaman ini via tombol "Back"
    window.addEventListener("pageshow", function(event) {
        // 'event.persisted' bernilai true jika halaman diambil dari cache memori browser
        var historyTraversal = event.persisted ||
            (typeof window.performance != "undefined" &&
                window.performance.navigation.type === 2);

        if (historyTraversal) {
            // Jika dari cache, paksa reload halaman agar angka pesan terupdate
            window.location.reload();
        }
    });
</script>
