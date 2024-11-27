<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'tb_akun';

    protected $fillable = [
        'nama',
        'kode',
    ];

    protected static function booted()
    {
        static::creating(function ($akun) {
            $akun->id_akun = uniqid();
        });
    }

    protected $primaryKey = 'id_akun';
    public $incrementing = false;
    protected $keyType = 'string';

    public function subAkun()
    {
        return $this->hasMany(SubAkun::class, 'id_akun');
    }
}
