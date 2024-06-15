<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use App\Models\Folder;
use App\Models\Note;
use App\Models\possede_categorie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class CategoryMassAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category-mass-assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer toutes les notes et les dossiers
        $ressources = collect();
        $notes = Note::all();
        $folders = Folder::all();
        $ressources = $ressources->merge($notes)->merge($folders);

        // Parcourir chaque ressource
        foreach ($ressources as $ressource) {
            // Extraire le chemin de la ressource
            $chemin = $ressource->path;

            // Extraire l'ID du dossier parent de la ressource
            $parentFolderId = Folder::getParentFolderIdFromPath($chemin);

            // Obtenez les catégories du dossier parent
            $parentCategories = Folder::getParentFolderCategories($parentFolderId);

            // Si la ressource est un dossier, ajoutez ses propres catégories également
            if ($ressource instanceof Folder) {
                $ressourceCategories = Folder::getFolderCategories($ressource->id);
                $parentCategories = $parentCategories->merge($ressourceCategories);
            }

            // Attribuer les catégories à la ressource
            foreach ($parentCategories as $categorie) {
                // Vérifier si la catégorie est déjà associée à la ressource
                if (!$ressource->categories->contains($categorie)) {
                    $ps = new possede_categorie();
                    $ps->ressource_id =  $ressource->id;
                    $ps->type_ressource = $ressource instanceof Note ? "note" : "folder";
                    $ps->categorie_id = $categorie->category_id;

                    // verification qu'il n'y a pas de doublons
                    if(possede_categorie::where([
                        ["ressource_id",$ressource->id],
                        ["type_ressource",$ps->type_ressource],
                        ["categorie_id",$categorie->category_id]
                    ])->get()){
                        $ps->owner_id = Auth::user()->id;
                        $ps->save();
                    }

                }
            }
        }
    }
}
