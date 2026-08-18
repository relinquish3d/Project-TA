<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryGame extends Model
{
 protected $table = 'Category_game';

 protected $fillable = [
    'game_name',
    'image',
 ];
}
