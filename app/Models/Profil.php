<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;

class Profil extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'tb_profil';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'alamat',
        'password',
        'id_role',
        'logo'
    ];

    protected $primaryKey = 'id_profil';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * logo
     *
     * @return Attribute
     */
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($logo) => url('/storage/logos/' . $logo),
        );
    }
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
