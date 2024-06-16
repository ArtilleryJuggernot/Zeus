<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Folder extends Model
{
    use HasFactory;
    public $table = "folders";
    public $primaryKey = "id";
    public $timestamps = false;

    public static function getParentFolderIdFromPath($folderPath)
    {
        // Supprimez le premier "/" s'il est présent
        $folderPath = ltrim($folderPath, '/');

        // Divisez le chemin en segments (dossiers)
        $segments = explode('/', $folderPath);

        // Supprimez le dernier segment (le nom du dossier actuel)
        array_pop($segments);

        // Rejoignez les segments restants pour obtenir le chemin du dossier parent
        $parentPath = implode('/', $segments);


        // Recherchez le dossier parent dans la base de données par son chemin
        $parentFolder = Folder::where('path', "/" . $parentPath)->first();


        // Retournez l'ID du dossier parent s'il existe, sinon null
        return $parentFolder ? $parentFolder->id : null;
    }


    public static function getParentFolderCategories($folderId)
    {
        // Recherchez les entrées de la table 'possede_categorie' pour le dossier parent donné
        $parentFolderCategories = possede_categorie::where([
            ['ressource_id', $folderId],
            ['type_ressource', 'folder']
        ])->pluck('categorie_id');

        // Recherchez les catégories correspondantes dans la table 'categories'
        $categories = Categorie::whereIn('category_id', $parentFolderCategories)->get();

        // Retournez les catégories du dossier parent
        return $categories;
    }


    public static function getFolderCategories($id,$user_id)
    {
        $resourceCategories = possede_categorie::where('ressource_id', $id)
            ->where('type_ressource', 'folder')
            ->where('owner_id', $user_id)
            ->get();

        $allCategories = Categorie::where('owner_id', $user_id)->get();

        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds);

        return $ownedCategories;
    }


    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'possede_categorie', 'ressource_id', 'categorie_id')
            ->where('possede_categorie.type_ressource', 'folder') // Spécifiez la table pour 'type_ressource'
            ->where('possede_categorie.owner_id', auth()->id()); // Spécifiez la table pour 'owner_id'
    }
}
