<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'tb_role';

    protected $fillable = [
        'role',
    ];

    protected static function booted()
    {
        static::creating(function ($role) {
            $role->id_role = uniqid();
        });
    }

    protected $primaryKey = 'id_role';
    public $incrementing = false;
    protected $keyType = 'string';
}
