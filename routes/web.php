<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Redirect root ke login atau dashboard
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // --- BAGIAN INI YANG TADI HILANG (ROUTE PROFILE) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // ---------------------------------------------------

    // 1. Dashboard: Tampilkan User + Jumlah Pesan Belum Dibaca
    Route::get('/dashboard', function () {
        $users = User::where('id', '!=', auth()->id())->get();

        // Hitung unread message untuk setiap user
        foreach ($users as $user) {
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();
        }

        return view('dashboard', ['users' => $users]);
    })->name('dashboard');

    // 2. Halaman Chat Room
    // 2. Chat Room: Saat dibuka, tandai semua pesan dari teman ini sebagai SUDAH DIBACA
    Route::get('/chat/{user}', function (User $user) {
        // Update is_read = true
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Ambil pesan (sama seperti sebelumnya)
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return view('chat', [
            'friend' => $user,
            'messages' => $messages
        ]);
    })->name('chat.show');

    // 3. Proses Kirim Pesan
    Route::post('/chat/{user}', function (Request $request, User $user) {
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message));

        return response()->json($message);
    });
});

require __DIR__ . '/auth.php';
