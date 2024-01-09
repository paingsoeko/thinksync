<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'process',
        'area_id',
        'status',
        'remark',
        'is_delete',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

}
