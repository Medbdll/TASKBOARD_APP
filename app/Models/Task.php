<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'priority',
        'status',
        'user_id'
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function isOverdue()
    {
        if (!$this->deadline || $this->status === 'done') {
            return false;
        }
        
        return $this->deadline->isPast();
    }
}
