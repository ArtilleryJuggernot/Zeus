<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class possede_categorie extends Model
{
    use HasFactory;
    public $table = "possede_categorie";
    public $primaryKey = "id";
    public $timestamps = false;

}
