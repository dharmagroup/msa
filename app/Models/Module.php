<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Module extends Model
{
    use HasFactory;

    protected $keyType = 'string'; // Tipe primary key


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->module_id = (string) Str::uuid(); // Menghasilkan UUID
        });
    }
}
