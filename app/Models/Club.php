<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_clubs', 'club_id', 'user_id');
    }
    public function materials(){
        return $this->hasMany(Material::class, 'club_id');
    }
}
