<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'expiration_date',
        'status_id',
        'priority_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }
}
