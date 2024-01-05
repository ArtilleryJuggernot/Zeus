<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $primaryKey = "task_id";
    public $table = "tasks";

    public function getPositionForProject($projectId)
    {
        return insideprojet::where('task_id', $this->task_id)
            ->where('projet_id', $projectId)
            ->value('pos');
    }
}
