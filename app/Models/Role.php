<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $keyType = 'string'; // Tipe primary key
    public $incrementing = false; // Non-incrementing
    protected $fillable = [
        'role_name'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->role_id = (string) Str::uuid(); // Menghasilkan UUID
        });
    }
}
