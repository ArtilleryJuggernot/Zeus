<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\insideprojet;
use App\Models\possede_categorie;
use App\Models\Projet;
use App\Models\Task;
use App\Models\task_priorities;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivreController extends Controller
{
    public function Overview()
    {
        $user_id = Auth::user()->id;
        $userLivreDone = $this->getLivreWithCategories($user_id, 1);
        $userLivreUnDone = $this->getLivreWithCategories($user_id, 0);

        return view("modules.livre.LivreOverview", [
            "userLivreDone" => $userLivreDone,
            "userLivreUnDone" => $userLivreUnDone,
        ]);
    }

    private function getLivreWithCategories($user_id,$is_finish)
    {
        $projects = Projet::where([
            ["owner_id", "=", $user_id],
            ["is_finish", "=", $is_finish],
            ["type","=","livre"]
        ])->get();

        foreach ($projects as $project) {
            $project->categories = $this->getLivreCategories($project->id, $user_id);
        }
        return $projects;
    }


    protected function getLivreCategories($projectId, $user_id)
    {
        $resourceCategories = possede_categorie::where('ressource_id', $projectId)
            ->where('type_ressource', 'project')
            ->where('owner_id', $user_id)
            ->get();

        $allCategories = Categorie::all()->where('owner_id', $user_id);

        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();

        return $ownedCategories;
    }


    public function Store(Request $request)
    {

        $user_id = Auth::user()->id;
        $livreName = $request->get('livre_name');
        $startPage = intval($request->get('startPage'));
        $endPage = intval($request->get('endPage'));
        $dtNum = intval($request->get('dt_num'));
        $deltaType = $request->get('delta_type');

        $startDate = Carbon::now();

        // Calculer le nombre total de pages à lire
        $totalPages = $endPage - $startPage + 1;

        // Vérifier que le nombre de pages de départ ne dépasse pas le nombre de pages d'arrivée
        if ($startPage > $endPage) {
            return redirect()->back()->with("error","Le nombre de pages de départ ne peut pas dépasser le nombre de pages d'arrivée.");
        }

        // Modifier la date limite en fonction du type de delta
        switch ($deltaType) {
            case 'jours':
                $endDate = $startDate->copy()->addDays($dtNum);
                break;
            case 'semaines':
                $endDate = $startDate->copy()->addWeeks($dtNum);
                break;
            case 'mois':
                $endDate = $startDate->copy()->addMonths($dtNum);
                break;
            default:
                $endDate = $startDate->copy()->addDays($dtNum); // Par défaut, utiliser le delta en jours
        }





        //dd("je reçois la requête");
        $tasksCount = $endDate->diff($startDate)->days;





        // Diviser le nombre de pages en restant en tranches égales
        $pagesPerTask = ceil($totalPages / $tasksCount);


        // Créer le projet
        $projet = new Projet();
        $projet->name = $livreName;
        $projet->type = 'livre'; // Type de projet livre
        $projet->owner_id = Auth::user()->id;
        $projet->save();
        LogsController::CreateProject($user_id,$projet->getKey(),$projet->name);

        $user_id = Auth::user()->id;

        // Créer les tâches
        for ($i = 0; $i < $tasksCount ; $i++) {
            $task = new Task();
            $task->owner_id = $user_id;
            $taskStartPage = $startPage + $i * $pagesPerTask;

            if($taskStartPage > $endPage)
                break;

            $taskEndPage = min($startPage + ($i + 1) * $pagesPerTask - 1, $endPage); // Limite supérieure est la dernière page

            // Définition du nom de la tâche
            $task->task_name = "(". $livreName . ") Lire les pages " . $taskStartPage . " à " . $taskEndPage;

            $task->description = "Lire les pages " . ($startPage + $i * $pagesPerTask) . " à " . ($startPage + ($i + 1) * $pagesPerTask - 1);

            // Calcul de la date limite en fonction de l'unité de temps spécifiée
            $task->due_date = $startDate->copy()->addDays($i); // Ajoute $i jours à la date de début
            $task->save();
            LogsController::createTask($user_id,$task->getKey(),$task->task_name,"SUCCESS");

            $inside_project = new insideprojet();
            $inside_project->task_id = $task->getKey();
            $inside_project->projet_id = $projet->getKey();
            $inside_project->pos = $i;
            $inside_project->save();
        }

        return redirect()->back()->with("success","Votre livre a bien été créé !");
    }


    // Fonction lancé dès la connexion de l'utilisateur
    // Met en priorité les tâches lié à la lecture à la date d'aujourd'hui pour qu'elle apparaisse sur le menu
    public static function SetTodayBookReadInPriority()
    {
        $user_id = Auth::user()->id;
        $ListbookProject = Projet::where([
            "owner_id" => $user_id,
            "type" => "livre"
        ])->get();


        foreach ($ListbookProject as $bookProject){
            $insideProjectTask = insideprojet::where("projet_id","=",$bookProject->id)->get();
            foreach ($insideProjectTask as $inside){
                $task = Task::find($inside->task_id);
                if($task->due_date == Carbon::today()->format("Y-m-d")){

                    if (count(task_priorities::where(
                        ["user_id" => $user_id,
                         "task_id" => $task->id
                        ]
                    )->get()) > 0)
                        break;

                    $priority = new task_priorities();
                    $priority->user_id = $user_id;
                    $priority->task_id = $task->id;
                    $priority->priority = "Prioritaire";
                    $priority->save();
                    break;
                }
            }

        }
    }



}
