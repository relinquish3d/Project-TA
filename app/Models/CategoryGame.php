<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryGame extends Model
{
    protected $table = 'categories_game';
    protected $primaryKey = 'id_category_game';

    protected $fillable = [
        'game_name',
        'image',
    ];
}
