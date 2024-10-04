<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Token extends Model
{
    use HasFactory;
    protected $keyType = 'string'; // Tipe primary key
    public $incrementing = false; // Non-incrementing
    protected $fillable = ['token','user_id','status'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token_id = (string) Str::uuid(); // Menghasilkan UUID
        });
    }
}
