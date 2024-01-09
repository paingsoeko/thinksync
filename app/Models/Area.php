<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'name',
        'process',
        'description',
        'is_delete',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
