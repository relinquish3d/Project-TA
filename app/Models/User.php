<?php

namespace App\Models;

use App\Models\Rating;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'email',
        'id_google',
        'email_verified_at',
        'password',
        'bio',
        'avatar',
        'role',
        'favorite_games',
        'active_status',
        'dark_mode',
        'messenger_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'favorite_games' => 'array',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            if (str_starts_with($this->avatar, 'avatars/') || str_starts_with($this->avatar, 'avatars_temp/')) {
                return asset('storage/' . $this->avatar);
            }
            return asset('storage/users-avatar/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C4C4C4&color=000000';
    }

    /* --- RELASI INVITE --- */
    public function sentInvite()
    {
        return $this->hasMany(Invite::class, 'sender_id', 'id');
    }

    public function receivedInvite()
    {
        return $this->hasMany(Invite::class, 'receiver_id', 'id');
    }

    public function inviteWith($userId)
    {
        return Invite::where(function ($q) use ($userId) {
            $q->where('sender_id', $this->id)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $this->id);
        })->first();
    }

    public function isFriendWith($userId): bool
    {
        $invite = $this->inviteWith($userId);
        return $invite && $invite->status === 'accepted';
    }

    /* --- RELASI CHAT / MESSAGE --- */
    public function lastMessageWith($authId)
    {
        return Message::where(function ($q) use ($authId) {
            $q->where('sender_id', $this->id)->where('receiver_id', $authId);
        })->orWhere(function ($q) use ($authId) {
            $q->where('sender_id', $authId)->where('receiver_id', $this->id);
        })->latest()->first();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'id');
    }

    public function last_message()
    {
        return $this->hasOne(Message::class, 'sender_id', 'id')->latestOfMany();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'id_user');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id_user');
    }

    /* --- RELASI REPORT --- */
    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id', 'id');
    }

    /* --- RELASI RATING --- */

    /**
     * Rating yang DITERIMA oleh user ini (orang lain merating user ini).
     * Gunakan: $user->ratingsReceived
     */
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'target_id', 'id');
    }

    /**
     * Rating yang DIBERIKAN oleh user ini (user ini merating orang lain).
     * Gunakan: $user->ratingsGiven
     */
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'reviewer_id', 'id');
    }

    /**
     * Hitung rata-rata rating yang diterima user.
     * Jika belum ada rating sama sekali, default 5.00.
     * Return: float (misal: 4.50, 3.75, 5.00)
     */
    public function averageRating(): float
    {
        $avg = $this->ratingsReceived()->avg('stars');
        return $avg !== null ? round((float)$avg, 2) : 5.00;
    }
}
