<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'area_id',
        'project_id',
        'urls',
        'status',
        'content',
        'remark',
        'is_delete',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
