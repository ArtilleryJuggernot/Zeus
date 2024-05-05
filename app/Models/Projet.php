<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;
    public $table = "Projet";
    public $primaryKey = "id";
    public $timestamps = false;

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'inside_projet', 'projet_id', 'task_id')
            ->withPivot('pos');
    }


}
