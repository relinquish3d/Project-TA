<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $table = 'Invite';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::Class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::Class, 'receiver_id');
    }
}
