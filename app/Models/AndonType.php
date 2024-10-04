<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AndonType extends Model
{
    use HasFactory;
    protected $fillable = [
        'andon_name'
    ];

    public function andonLines()
    {
        return $this->hasMany(AndonLine::class, 'andon_type_id', 'id');
    }
}
