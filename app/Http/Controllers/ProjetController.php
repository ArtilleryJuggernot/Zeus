<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Categorie;
use App\Models\insideprojet;
use App\Models\possede_categorie;
use App\Models\Projet;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetController extends Controller
{
    public function Overview()
    {
        $user_id = Auth::user()->id;
        $userProjetsDone = $this->getProjectsListWithCategories($user_id, 1);
        $userProjetsUnDone = $this->getProjectsListWithCategories($user_id, 0);

        return view("projet.ProjetOverview", [
            "userProjetsDone" => $userProjetsDone,
            "userProjectUnDone" => $userProjetsUnDone,
        ]);
    }

    protected function getProjectsListWithCategories($user_id, $is_finish)
    {
        $projects = Projet::where([
            ["owner_id", "=", $user_id],
            ["is_finish", "=", $is_finish],
        ])->get();

        foreach ($projects as $project) {
            $project->categories = $this->getProjectCategories($project->id, $user_id);
        }

        return $projects;
    }

    protected function getProjectCategories($projectId, $user_id)
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


    public function View(int $id)
    {
        $user_id = Auth::user()->id;
        $projet = Projet::with('tasks')->find($id);

        if (!$projet)
            return redirect()->route("home")->with("failure", "Le projet que vous souhaitez voir n'existe pas");

        $taskFinish = $projet->tasks->where("is_finish", 1);
        $taskTODO = $projet->tasks->where("is_finish", 0);

        foreach ($taskTODO as $task) {
            $task->pos = $task->getPositionForProject($id);
        }

        if ((count($taskTODO) + count($taskFinish)) == 0) $progression = 100; // Division par 0

        else $progression = (count($taskFinish) / (count($taskTODO) + count($taskFinish))) * 100;

        //dd($tasks);


        $usersPermissionsOnNote = Acces::getUsersPermissionsOnProject($id);
        $perm_user = 0;
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces) {
            if ($acces->dest_id == $user_id) {
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }
        $usersPermissionsOnNote = Acces::getUsersPermissionsOnProject($id);
        $perm_user = 0;
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces) {
            if ($acces->dest_id == $user_id) {
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }


        $resourceCategories = possede_categorie::where('ressource_id', $id)
            ->where('type_ressource', "project")
            ->where('owner_id', $user_id)->get();

// Obtenez toutes les catégories en utilisant le modèle Categorie
        $allCategories = Categorie::all()->where("owner_id", $user_id);

// Obtenez les catégories possédées par la ressource
        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();

// Séparez les catégories possédées et non possédées
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();
        $notOwnedCategories = $allCategories->whereNotIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();


        if ($projet->owner_id == $user_id || $autorisation_partage) { // TODO : Modif pour collab
            return view("projet.ProjetView",
                [
                    "projet" => $projet,
                    "taskFinish" => $taskFinish,
                    "taskTODO" => $taskTODO,
                    "progression" => $progression,
                    "usersPermissionList" => $usersPermissionsOnNote,
                    "perm_user" => $perm_user,
                    "ressourceCategories" => $resourceCategories,
                    "ownedCategories" => $ownedCategories,
                    "notOwnedCategories" => $notOwnedCategories
                ]);
        }


        return redirect()->route("home")->with("failure", "Vous n'êtes pas autorisé à voir cette ressource");
    }


    public function AddTask(Request $request)
    {
        //dd($request);
        if ($request->has('is_due')) {
            $validateData = $request->validate([
                "tache_name" => ["required", "string", "max:250"],
                "is_due" => ["nullable", "in:on,off"],
                "dt_input" => ["nullable", "date"],
                "project_id" => ["required", "integer"]
            ]);
        } else {
            $validateData = $request->validate([
                "tache_name" => ["required", "string", "max:250"],
                "project_id" => ["required", "integer"]
            ]);
        }


        $name = $validateData["tache_name"];
        $task = new Task();
        $task->task_name = $name;
        $task->owner_id = Auth::user()->id;

        if ($request->has("is_due") && $validateData["is_due"] == "on") {
            $task->due_date = $validateData["dt_input"]; // Date limite
        }
        $task->save();


        // Inside project save
        $projet_id = $validateData["project_id"];

        $inside = new insideprojet();
        $inside->task_id = $task->task_id;
        $inside->projet_id = $projet_id;

        // Get la nouvelle pos  = max + 1
        $max = InsideProjet::where('projet_id', $projet_id)
            ->max('pos');
        $inside->pos = $max + 1;
        $inside->save();

        return redirect()->back()->with(["success" => "La tâche à bien été créer"]);

    }

    public function Store(Request $request)
    {
        //dd($request);
        $validateData = $request->validate([
            "projet_name" => ["required", "string", "max:250"]
        ]);
        $name = $validateData["projet_name"];
        $projet = new Projet();
        $projet->name = $name;
        $projet->owner_id = Auth::user()->id;
        $projet->save();
        return redirect()->back()->with(["success" => "Le projet à bien été créer"]);
    }

    public function RemoveTaskFromProject(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required", "integer"],
            "project_id" => ["required", "integer"]
        ]);
        $Task_id = $validateData["task_id"];
        $Project_id = $validateData["project_id"];

        $projet = Projet::find($Project_id);

        if (!$projet) return redirect()->route("home")->with("failure", "Le projet auxquel vous tentez d'accéder n'existe pas");

        $projet->tasks()->detach($Task_id);
        Task::find($Task_id)->delete();
        return redirect()->back()->with(["success" => "Tâche supprimé du projet avec succès"]);
    }

    // TaskTODO -> TaskDone
    public function CheckTaskTODO(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required", "integer"]
        ]);
        $id = $validateData["task_id"];
        $task = Task::find($id);

        if (!$task) return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");

        $task->is_finish = true;
        $task->save();
        return redirect()->back()->with(["success" => "Tâche du projet validée avec succès"]);
    }

    // TaskDone -> TaskTODO
    public function UncheckTaskDone(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required", "integer"]
        ]);
        $id = $validateData["task_id"];
        $task = Task::find($id);

        if (!$task) return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");

        $task->is_finish = false;
        $task->save();
        return redirect()->back()->with(["success" => "Tâche remise dans le projet avec succès"]);
    }


    public function Delete(Request $request)
    {
        $validateData = $request->validate([
            "project_id" => ["integer", "required"]
        ]);
        $user_id = Auth::user()->id;
        $project_id = $validateData["project_id"];

        $projet = Projet::find($project_id);

        if (!$projet) return redirect()->route("home")->with("failure", "La projet que vous souhaitez supprimé n'a pas été trouvé");

        if ($projet->owner_id != $user_id) return redirect()->route("home")->with("failure", "Vous n'êtes pas autoriser à modifier sur cette ressource");

        $name = $projet->name;

        // Avant de supprimé le projet, on supprime les tâche qui lui sont associé

        insideprojet::where("projet_id", $project_id)->delete();


        // Supprimer les droits associés à une note

        Acces::where([
            ["ressource_id", $project_id],
            ["type", "project"],
        ])->delete();


        // Supprimer les catégories associés à la note

        possede_categorie::where([
            ["ressource_id", $project_id],
            ["type_ressource", "project"]
        ])->delete();

        $projet->delete();


        return redirect()->back()->with("success", "Le projet " . $name . " a bien été supprimé.");

    }

    public function CheckToggleAsDone(Request $request)
    {
        $validateData = $request->validate([
            "project_id" => ["integer", "required"]
        ]);
        $user_id = Auth::user()->id;
        $project_id = $validateData["project_id"];

        $projet = Projet::find($project_id);

        if (!$projet) return redirect()->route("home")->with("failure", "La projet que vous souhaitez supprimé n'a pas été trouvé");

        if ($projet->owner_id != $user_id) return redirect()->route("home")->with("failure", "Vous n'êtes pas autoriser à modifier sur cette ressource");

        $name = $projet->name;


        $msg = " a bien été archiver";
        if ($projet->is_finish) {
            $msg = " a bien été dé-archiver";
            $projet->is_finish = false;
        } else $projet->is_finish = true;


        $projet->save();

        return redirect()->back()->with("success", "Le projet " . $name . $msg);


    }






}
