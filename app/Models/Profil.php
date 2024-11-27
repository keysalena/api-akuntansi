<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Profil extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'tb_profil';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'id_role',
    ];

    protected $primaryKey = 'id_profil';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($profil) {
            $profil->id_profil = uniqid();
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
