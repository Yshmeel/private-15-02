<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'max_users'
    ];

    public $visible = [
        'name',
        'max_users'
    ];

    public function users() {
        return $this->hasMany(User::class, 'selected_room_id');
    }

    public function messages() {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'ASC');
    }
}
