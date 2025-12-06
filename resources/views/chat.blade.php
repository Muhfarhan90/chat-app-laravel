<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chat dengan {{ $friend->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div id="chat-box" class="h-96 overflow-y-auto border p-4 mb-4 bg-gray-50 flex flex-col gap-2">
                    @foreach ($messages as $msg)
                        <div class="{{ $msg->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                            <span
                                class="inline-block p-2 rounded {{ $msg->sender_id == auth()->id() ? 'bg-blue-200' : 'bg-gray-200' }}">
                                {{ $msg->message }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <form id="form-chat" class="flex gap-2">
                    <input type="text" id="message-input" class="w-full border rounded p-2"
                        placeholder="Tulis pesan..." required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kirim</button>
                </form>

            </div>
        </div>
    </div>

    <script type="module">
        const friendId = {{ $friend->id }};
        const chatBox = document.getElementById('chat-box');

        // Scroll ke bawah saat halaman dimuat
        chatBox.scrollTop = chatBox.scrollHeight;
        // KITA CUKUP DENGARKAN UNTUK MENAMPILKAN PESAN SAJA
        // Urusan suara & notifikasi sudah diurus oleh app.blade.php
        Echo.private('user.' + {{ auth()->id() }})
            .listen('.message.sent', (e) => {
                if (e.message.sender_id === friendId) {
                    appendMessage(e.message.message, 'left');

                    // Opsional: Tandai sudah dibaca via AJAX background agar badge hilang nanti
                    // (Anda bisa tambahkan fetch ke route mark-read disini jika mau canggih)
                }
            });

        // Fungsi Append ke Chatbox (Sama seperti sebelumnya)
        function appendMessage(text, side) {
            const div = document.createElement('div');
            div.className = side === 'right' ? 'text-right' : 'text-left';

            const span = document.createElement('span');
            span.className = `inline-block p-2 rounded ${side === 'right' ? 'bg-blue-200' : 'bg-gray-200'}`;
            span.innerText = text;

            div.appendChild(span);
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Handle Form Submit (Kirim Pesan)
        document.getElementById('form-chat').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const message = input.value;

            if (!message) return; // Jangan kirim kosong

            axios.post('/chat/' + friendId, {
                    message: message
                })
                .then(response => {
                    appendMessage(message, 'right');
                    input.value = '';
                })
                .catch(error => console.error(error));
        });
    </script>
</x-app-layout>
