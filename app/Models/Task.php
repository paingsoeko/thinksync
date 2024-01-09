<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'due_date',
        'area_id',
        'project_id',
        'resource_id',
        'status',
        'remark',
        'is_delete',
    ];
}
