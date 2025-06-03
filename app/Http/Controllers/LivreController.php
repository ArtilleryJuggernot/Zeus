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
    /**
     * Affiche la vue d'ensemble des livres de l'utilisateur (en cours et terminés).
     */
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

    /**
     * Récupère les projets de type 'livre' pour l'utilisateur, avec leurs catégories associées.
     * @param int $user_id
     * @param int $is_finish 0 = en cours, 1 = terminé
     * @return \Illuminate\Support\Collection
     */
    private function getLivreWithCategories($user_id, $is_finish)
    {
        $projects = Projet::where([
            ["owner_id", "=", $user_id],
            ["is_finish", "=", $is_finish],
            ["type", "=", "livre"]
        ])->get();

        foreach ($projects as $project) {
            $project->categories = $this->getLivreCategories($project->id, $user_id);
        }
        return $projects;
    }

    /**
     * Récupère les catégories associées à un projet de type livre.
     * @param int $projectId
     * @param int $user_id
     * @return array
     */
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

    /**
     * Crée un nouveau projet de type livre, découpe le livre en tâches (pages à lire par jour/semaine/mois),
     * et crée les tâches associées avec les bonnes dates limites.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Store(Request $request)
    {
        $user_id = Auth::user()->id;
        $livreName = $request->get('livre_name');
        $startPage = intval($request->get('startPage'));
        $endPage = intval($request->get('endPage'));
        $dtNum = intval($request->get('dt_num'));
        $deltaType = $request->get('delta_type');

        $startDate = Carbon::now();

        // Vérification des entrées utilisateur
        if ($startPage < 0 || $endPage < 0 || $dtNum <= 0) {
            return redirect()->back()->with("error", "Merci de remplir tous les champs correctement (aucune valeur négative, durée > 0).");
        }
        if ($startPage > $endPage) {
            return redirect()->back()->with("error", "Le nombre de pages de départ ne peut pas dépasser le nombre de pages d'arrivée.");
        }
        if ($startPage == $endPage) {
            return redirect()->back()->with("error", "Le livre doit contenir au moins 1 page à lire.");
        }

        // Calcul du nombre total de pages à lire
        $totalPages = $endPage - $startPage + 1;

        // Calcul de la date limite en fonction du type de delta
        switch ($deltaType) {
            case 'jours':
                $endDate = $startDate->copy()->addDays($dtNum);
                $tasksCount = $dtNum;
                break;
            case 'semaines':
                $endDate = $startDate->copy()->addWeeks($dtNum);
                $tasksCount = $dtNum * 7;
                break;
            case 'mois':
                $endDate = $startDate->copy()->addMonths($dtNum);
                $tasksCount = $dtNum * 30; // Approximation : 1 mois = 30 jours
                break;
            default:
                $endDate = $startDate->copy()->addDays($dtNum);
                $tasksCount = $dtNum;
        }

        // On ne crée pas plus de tâches que de pages
        $tasksCount = min($tasksCount, $totalPages);
        if ($tasksCount <= 0) {
            return redirect()->back()->with("error", "Impossible de découper le livre en tâches (vérifiez vos paramètres).");
        }

        // Calcul du nombre de pages par tâche (arrondi supérieur)
        $pagesPerTask = ceil($totalPages / $tasksCount);

        // Création du projet (livre)
        $projet = new Projet();
        $projet->name = $livreName;
        $projet->type = 'livre'; // Type de projet livre
        $projet->owner_id = $user_id;
        $projet->save();
        LogsController::CreateProject($user_id, $projet->getKey(), $projet->name);

        // Création des tâches (une par tranche de pages)
        for ($i = 0; $i < $tasksCount; $i++) {
            $task = new Task();
            $task->owner_id = $user_id;
            $task->type = "livre";
            $taskStartPage = $startPage + $i * $pagesPerTask;
            if ($taskStartPage > $endPage) break;
            $taskEndPage = min($startPage + ($i + 1) * $pagesPerTask - 1, $endPage);
            $task->task_name = "(" . $livreName . ") 📖 Lire les pages " . $taskStartPage . " à " . $taskEndPage;
            $task->description = "Lire les pages " . $taskStartPage . " à " . $taskEndPage;
            // Date limite = $i jours après la date de début
            $task->due_date = $startDate->copy()->addDays($i);
            $task->save();
            LogsController::createTask($user_id, $task->getKey(), $task->task_name, "SUCCESS");
            $inside_project = new insideprojet();
            $inside_project->task_id = $task->getKey();
            $inside_project->projet_id = $projet->getKey();
            $inside_project->pos = $i;
            $inside_project->save();
        }

        return redirect()->back()->with("success", "Votre livre a bien été créé !");
    }

    /**
     * Fonction appelée à la connexion de l'utilisateur.
     * Met en priorité les tâches de lecture du jour pour qu'elles apparaissent sur le menu principal.
     */
    public static function SetTodayBookReadInPriority()
    {
        $user_id = Auth::user()->id;
        $ListbookProject = Projet::where([
            "owner_id" => $user_id,
            "type" => "livre"
        ])->get();

        foreach ($ListbookProject as $bookProject) {
            $insideProjectTask = insideprojet::where("projet_id", "=", $bookProject->id)->get();
            foreach ($insideProjectTask as $inside) {
                $task = Task::find($inside->task_id);
                if ($task && $task->due_date == Carbon::today()->format("Y-m-d")) {
                    // On ne crée la priorité que si elle n'existe pas déjà
                    if (task_priorities::where([
                        "user_id" => $user_id,
                        "task_id" => $task->id
                    ])->count() > 0) {
                        break;
                    }
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
