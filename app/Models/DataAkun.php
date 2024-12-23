<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAkun extends Model
{
    use HasFactory;

    protected $table = 'tb_data_akun';

    protected $fillable = [
        'nama',
        'kode',
        'id_sub_akun',
        'id_profil',
        'debit',
        'kredit',
    ];

    protected static function booted()
    {
        static::creating(function ($data_akun) {
            $data_akun->id_data_akun = uniqid();
        });
    }
    public function subAkun()
    {
        return $this->belongsTo(SubAkun::class, 'id_sub_akun');
    }
    public function jurnal()
    {
        return $this->hasMany(Jurnal::class, 'id_tipe_jurnal', 'id_tipe_jurnal');
    }
    protected $primaryKey = 'id_data_akun';
    public $incrementing = false;
    protected $keyType = 'string';
}
