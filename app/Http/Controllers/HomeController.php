<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Models\possede_categorie;
use App\Models\Task;
use App\Models\task_priorities;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    function HomeView()
    {
        $user = Auth::user();



        $this::attribuerCategoriesAuxRessources();

        // Mettre en priorité les tâches à faire aujourd'hui (Livre)

        LivreController::SetTodayBookReadInPriority();


        // Tache à faire avec date limite en cours
        $tachesTimed  = Task::whereDoesntHave('projects', function($query) {
        $query->where('type', 'livre');
    })->where([
        ["owner_id", $user->id],
        ["due_date", ">", Carbon::today()],
        ["is_finish", 0]
    ])->get();




        // Tache à faire passé
        $tachesPasse = Task::where([
            ["owner_id",$user->id],
            ["due_date","<",Carbon::today()],
            ["is_finish",0]
        ])->get();


        // Tache en priorité

        $task_priorities = task_priorities::where('user_id', $user->id)
            ->whereHas('task', function ($query) {
                $query->where('is_finish', false);
            })
            ->get();

        $task_priority = PriorityController::sortTasksByPriority($task_priorities);





        return view("home",[
            "user" => $user,
            "tachesTimed" => $tachesTimed,
            "tachePasse" => $tachesPasse,
            "task_priority" => $task_priority,
        ]);
    }

    function AboutView(){
        return view("about.about");
    }


    private static function attribuerCategoriesAuxRessources() : void
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
                    $ps->owner_id = Auth::user()->id;
                    $ps->save();
                }
            }
        }
    }




}
