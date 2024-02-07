<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logs extends Model
{
    use HasFactory;

    public  $table = "logs";
    protected $primaryKey = "id";

    public $timestamps = false;

}
