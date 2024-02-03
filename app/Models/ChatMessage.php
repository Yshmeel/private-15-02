<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    public $fillable = [
        'message',
        'user_id',
        'room_id',
        'created_at'
    ];

    public $visible = [
        'message',
        'user_id',
        'room_id',
        'created_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }
}
