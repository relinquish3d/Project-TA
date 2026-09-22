<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'category',
        'description',
        'attachment',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        $categories = [
            'fake_profile' => 'Profil Palsu / Penipuan',
            'harassment' => 'Pelecehan / Kata-kata Kasar',
            'inappropriate_content' => 'Konten Tidak Pantas',
            'spam' => 'Spam / Aktivitas Mencurigakan',
            'other' => 'Lainnya',
        ];
        return $categories[$this->category] ?? ucfirst($this->category ?? 'Lainnya');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'completed' ? 'Selesai' : 'Sedang Diproses';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'completed'
            ? 'bg-emerald-100 text-emerald-700 border-emerald-300'
            : 'bg-amber-100 text-amber-700 border-amber-300';
    }

    public function getTicketCodeAttribute(): string
    {
        return '#REP-' . str_pad((string)$this->id, 5, '0', STR_PAD_LEFT);
    }
}