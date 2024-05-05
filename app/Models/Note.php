<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = "id";

    public $timestamps = true;
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


    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'possede_categorie', 'ressource_id', 'categorie_id')
            ->where('possede_categorie.type_ressource', 'note') // Spécifiez la table pour 'type_ressource'
            ->where('possede_categorie.owner_id', auth()->id()); // Spécifiez la table pour 'owner_id'
    }


}
