<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitude extends Model
{
    use HasFactory;
    protected $table = "habitude";
    public $timestamps = false;

}
