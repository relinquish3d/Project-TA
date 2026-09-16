<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $table = 'invite';

    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    // User yang mengirim permintaan add
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // User yang menerima permintaan add
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}

