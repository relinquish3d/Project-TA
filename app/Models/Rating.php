<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Rating
 * 
 * Menyimpan data rating/ulasan antar pengguna.
 * - reviewer_id: ID user yang memberi rating
 * - target_id:   ID user yang diberi rating
 * - stars:       Nilai rating (0.5 - 5.0, bisa setengah bintang)
 * - comment:     Ulasan teks (opsional, nullable)
 */
class Rating extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'rating';

    // Primary key tabel
    protected $primaryKey = 'id_rating';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'reviewer_id',
        'target_id',
        'stars',
        'comment',
    ];

    /**
     * Relasi ke user yang MEMBERI rating (reviewer).
     * Gunakan: $rating->reviewer->name
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
    }

    /**
     * Relasi ke user yang DIBERI rating (target).
     * Gunakan: $rating->target->name
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }
}
