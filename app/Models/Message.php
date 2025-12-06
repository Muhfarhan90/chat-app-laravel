<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'message', 'is_read'];
    // Relasi (Opsional, agar mudah memanggil nama)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
