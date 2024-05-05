<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task_priorities extends Model
{
    use HasFactory;
    protected $table = "task_priorities";


    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

}
