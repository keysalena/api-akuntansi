<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeJurnal extends Model
{
    use HasFactory;

    protected $table = 'tb_tipe_jurnal';

    protected $fillable = [
        'nama',
    ];

    protected static function booted()
    {
        static::creating(function ($tipe_jurnal) {
            $tipe_jurnal->id_tipe_jurnal = uniqid();
        });
    }

    protected $primaryKey = 'id_tipe_jurnal';
    public $incrementing = false;
    protected $keyType = 'string';
    public function jurnal()
    {
        return $this->hasMany(Jurnal::class, 'id_tipe_jurnal', 'id_tipe_jurnal');
    }
}
