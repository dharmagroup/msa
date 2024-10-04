<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AndonLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'andon_type',
        'andon_line',
        'andon_station',
        'status',
        'reason',
        'repairTime'
    ];
    public function timer()
    {
        return $this->hasOne(AndonTimer::class, 'andon_log_id', 'id');
    }

    public function andonType()
    {
        return $this->belongsTo(AndonType::class, 'andon_type', 'id'); // Sesuaikan dengan kolom yang tepat
    }
}
