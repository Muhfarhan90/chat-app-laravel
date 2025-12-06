<?php

use Illuminate\Support\Facades\Broadcast;

// Pastikan nama parameternya 'id', bukan 'user' atau yang lain
Broadcast::channel('user.{id}', function ($user, $id) {
    // Logika: Apakah ID user yang login == ID channel yang mau dimasuki?
    // Casting (int) sangat penting karena kadang $id terbaca sebagai string "1"
    return (int) $user->id === (int) $id;
});
