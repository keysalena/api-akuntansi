<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAkun extends Model
{
    use HasFactory;

    protected $table = 'tb_sub_akun';

    protected $fillable = [
        'nama',
        'kode',
        'id_akun',
    ];

    protected static function booted()
    {
        static::creating(function ($sub_akun) {
            $sub_akun->id_sub_akun = uniqid();
        });
    }

    protected $primaryKey = 'id_sub_akun';
    public $incrementing = false;
    protected $keyType = 'string';

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }
}
