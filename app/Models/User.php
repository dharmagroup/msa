<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $keyType = 'string'; // Tipe primary key
    public $incrementing = false; // Non-incrementing

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = (string) Str::uuid(); // Menghasilkan UUID
        });
    }
}
