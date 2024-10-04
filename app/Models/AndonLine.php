<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AndonLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'andon_line_name',
        'andon_type_id',
        'andon_audio_path'
    ];

    public function logs()
    {
        return $this->hasMany(AndonLog::class, 'andon_line', 'andon_line_name'); // Sesuaikan dengan kolom yang tepat
    }

}
