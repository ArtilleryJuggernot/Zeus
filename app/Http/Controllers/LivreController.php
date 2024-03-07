<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\insideprojet;
use App\Models\possede_categorie;
use App\Models\Projet;
use App\Models\Task;
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


    public function View()
    {

    }

    public function Store(Request $request)
    {
        $livreName = $request->get('livre_name');
        $startPage = intval($request->get('startPage'));
        $endPage = intval($request->get('endPage'));
        $dtNum = intval($request->get('dt_num'));
        $deltaType = $request->get('delta_type');

        $startDate = Carbon::now();

        // Modification après en fonction de $deltaType
        $endDate = Carbon::now();

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate->format("Y-m-d"));

// Calculer le nombre total de pages à lire
        $totalPages = $endPage - $startPage + 1;

// Calculer le nombre de pages par tâche en fonction du delta de temps
        $pagesPerTask = ceil($totalPages / $dtNum);

// Initialiser une nouvelle variable pour stocker le nombre de tâches en fonction de l'unité de temps
        $tasksCount = 0;

// Calculer le nombre de tâches en fonction de l'unité de temps
        switch ($deltaType) {
            case 'jours':

                $endDate = $endDate->addDays($dtNum);

                // Calculer l'intervalle entre la date de début et la date limite
                $interval = $startDate->diff($endDate);

                // Diviser le nombre de jours entre la date de début et la date limite par le delta de temps
                //dd($endDate->diffInDays($startDate));
                $tasksCount = ceil($endDate->diffInDays($startDate));
                break;
            case 'semaines':


                $endDate->addWeeks($dtNum);
                $interval = $startDate->diff($endDate);
                // Diviser le nombre de semaines entre la date de début et la date limite par le delta de temps
                $tasksCount = ceil($endDate->diffInWeeks($startDate) * 7);
                break;
            case 'mois':

                $endDate->addMonths($dtNum);
                $interval = $startDate->diff($endDate);
                // Diviser le nombre de mois entre la date de début et la date limite par le delta de temps
                $tasksCount = ceil($endDate->diffInMonths($startDate) * 30);
                break;


        }

// Diviser le nombre de pages en restant en tranches égales
        $dtNum = $tasksCount;

// Calculer l'intervalle de temps pour chaque tâche en fonction de l'unité de temps
        switch ($deltaType) {
            case 'jours':
                $taskInterval = 'days';
                break;
            case 'semaines':
                $taskInterval = 'weeks';
                break;
            case 'mois':
                $taskInterval = 'months';
                break;
            default:
                $taskInterval = 'days'; // Par défaut, utiliser un jour
        }



// Créer le projet
        $projet = new Projet();
        $projet->name = $livreName;
        $projet->type = 'livre'; // Type de projet livre
        $projet->owner_id = Auth::user()->id;
        $projet->save();

// Créer les tâches
        for ($i = 0; $i < $dtNum ; $i++) {
            $task = new Task();

            $taskStartPage = $startPage + $i * $pagesPerTask;
            $taskEndPage = min($startPage + ($i + 1) * $pagesPerTask - 1, $endPage); // Limite supérieure est la dernière page

            // Définition du nom de la tâche
            $task->task_name = "Lire les pages " . $taskStartPage . " à " . $taskEndPage;

            $task->description = "Lire les pages " . ($startPage + $i * $pagesPerTask) . " à " . ($startPage + ($i + 1) * $pagesPerTask - 1);
            $task->due_date = $startDate->copy()->add($i * $dtNum, $taskInterval);
            $task->save();
            $inside_project = new insideprojet();
            $inside_project->task_id = $task->getKey();
            $inside_project->projet_id = $projet->getKey();
            $inside_project->pos = $i;
            $inside_project->save();
        }

        return redirect()->back()->with("success","Votre livre à bien été créer !");

    }
}
