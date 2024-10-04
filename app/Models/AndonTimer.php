<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AndonTimer extends Model
{
    use HasFactory;
    protected $fillable = [
        'andon_log_id',
        'start',
        'end'
    ];
}
