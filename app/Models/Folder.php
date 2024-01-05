<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    public $table = "folders";
    public $primaryKey = "folder_id";
    public $timestamps = false;
}
