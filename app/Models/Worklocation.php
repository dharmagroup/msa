<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Worklocation extends Model
{
    use HasFactory;

    protected $keyType = 'string'; // Tipe primary key
    public $incrementing = false; // Non-incrementing

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->worklocation_id = (string) Str::uuid(); // Menghasilkan UUID
        });
    }
}
