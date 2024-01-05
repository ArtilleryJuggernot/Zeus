<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = "note_id";
    public static function getNoteContentByPath($path)
    {
        // Vérifier si le fichier existe
        if (Storage::disk('local')->exists($path)) {
            // Lire le contenu du fichier et le retourner
            return Storage::disk('local')->get($path);
        } else {
            return null; // Retourner null si le fichier n'existe pas
        }
    }
    public $timestamps = true;

}
