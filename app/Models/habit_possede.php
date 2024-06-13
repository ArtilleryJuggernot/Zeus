<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class habit_possede extends Model
{
    use HasFactory;
    protected $table = "habit_possede";
    public $timestamps = false;
    protected $primaryKey = ['habit_id', 'day_id'];
    protected $fillable = ['habit_id', 'day_id', 'start', 'stop'];
public $incrementing = false;

}
