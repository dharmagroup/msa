<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class RoleGroup extends Model
{
    use HasFactory;
    protected $keyType = 'string'; // Tipe primary key
    public $incrementing = false; // Non-incrementing
    protected $fillable = [
        'role_id',
        'module_id'
        
    ];
    protected static function boot()
    {
        parent::boot();
      
        static::creating(function ($model) {
            $model->role_group_id = (string) Str::uuid(); // Menghasilkan UUID
        });
    }

    public function make()
    {
        return $this->hasMany(RoleGroup::class);
    }
}
