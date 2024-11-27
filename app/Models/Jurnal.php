<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'tb_jurnal';

    protected $fillable = [
        'id_tipe_jurnal',
        'tanggal',
        'nama_transaksi',
        'nominal',
        'id_debit',
        'id_kredit',
        'id_profil',
        'id_role',
    ];

    protected static function booted()
    {
        static::creating(function ($jurnal) {
            $jurnal->id_jurnal = uniqid();
        });
    }

    protected $primaryKey = 'id_jurnal';
    public $incrementing = false;
    protected $keyType = 'string';

    public function debitAccount()
    {
        return $this->belongsTo(DataAkun::class, 'id_debit', 'id_data_akun');
    }
    
    public function kreditAccount()
    {
        return $this->belongsTo(DataAkun::class, 'id_kredit', 'id_data_akun');
    }    

    public function tipe_jurnal()
    {
        return $this->belongsTo(TipeJurnal::class, 'id_tipe_jurnal', 'id_tipe_jurnal');
    }
}
