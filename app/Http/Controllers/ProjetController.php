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
        $categories = Categorie::where('owner_id', $user_id)->get();
        return view("projet.ProjetOverview", [
            "userProjetsDone" => $userProjetsDone,
            "userProjectUnDone" => $userProjetsUnDone,
            "categories" => $categories
        ]);
    }

    protected function getProjectsListWithCategories($user_id, $is_finish)
    {
        $projects = Projet::where([
            ["owner_id", "=", $user_id],
            ["is_finish", "=", $is_finish],
            ["type" ,"=","none"]
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


    public function View(int $id, Request $request)
    {
        $user_id = Auth::user()->id;
        $projet = Projet::with(['tasks.categories'])->find($id);

        if (!$projet)
            return redirect()->route("home")->with("failure", "Le projet que vous souhaitez voir n'existe pas");

        // Gestion du tri
        $sort = $request->get('sort', 'position'); // position (défaut), date_asc, date_desc
        $tasks = $projet->tasks;
        if ($sort === 'date_asc') {
            $tasks = $tasks->sortBy('created_at');
        } elseif ($sort === 'date_desc') {
            $tasks = $tasks->sortByDesc('created_at');
        } else { // par position (défaut)
            $tasks = $tasks->sortBy(function($task) use ($id) {
                return $task->getPositionForProject($id);
            });
        }

        $taskFinish = $tasks->where("is_finish", 1);
        $taskTODO = $tasks->where("is_finish", 0);

        foreach ($taskTODO as $task) {
            $task->pos = $task->getPositionForProject($id);
        }
        foreach ($taskFinish as $task) {
            $task->pos = $task->getPositionForProject($id);
        }

        if ((count($taskTODO) + count($taskFinish)) == 0) $progression = 100; // Division par 0
        else $progression =   round((count($taskFinish) / (count($taskTODO) + count($taskFinish))) * 100,3);

        $tasksNotInProject = Task::where([
            ["owner_id", $user_id],
            ["is_finish", 0],
        ])
            ->whereDoesntHave('projects')
            ->get();

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
        $allCategories = Categorie::all()->where("owner_id", $user_id);
        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();
        $notOwnedCategories = $allCategories->whereNotIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();
        $categories = Categorie::where('owner_id', $user_id)->get();

        if ($projet->owner_id == $user_id || $autorisation_partage) {
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
                    "notOwnedCategories" => $notOwnedCategories,
                    "tasksNotInProject" => $tasksNotInProject,
                    "categories" => $categories,
                    "sort" => $sort
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

        $user_id = Auth::user()->id;

        $name = $validateData["tache_name"];
        $task = new Task();
        $task->task_name = $name;
        $task->owner_id = $user_id;
        $task->description = "# " . $name;
        if ($request->has("is_due") && $validateData["is_due"] == "on") {
            $task->due_date = $validateData["dt_input"]; // Date limite
        }
        $task->save();


        // Inside project save
        $projet_id = $validateData["project_id"];

        $inside = new insideprojet();
        $inside->task_id = $task->id;
        $inside->projet_id = $projet_id;

        // Get la nouvelle pos  = max + 1
        $max = InsideProjet::where('projet_id', $projet_id)
            ->max('pos');
        $inside->pos = $max + 1;
        $inside->save();
        $task_id = $task->getKey();
        LogsController::createTask($user_id,$task->getKey(),$name,"SUCCESS");
        CategorieController::HeritageCategorieProjectToTask($task_id,$projet_id);
        return redirect()->back()->with(["success" => "La tâche à bien été créer"]);

    }

    public function Store(Request $request)
    {
        $validateData = $request->validate([
            "projet_name" => ["required", "string", "max:250"]
        ]);
        $user_id = Auth::user()->id;
        $name = $validateData["projet_name"];
        $projet = new Projet();
        $projet->name = $name;
        $projet->owner_id = $user_id;
        $projet->save();
        LogsController::CreateProject($user_id,$projet->getKey(),$projet->name);
        // Association des catégories sélectionnées
        if ($request->has('categories')) {
            foreach ($request->input('categories') as $cat_id) {
                possede_categorie::create([
                    'ressource_id' => $projet->id,
                    'categorie_id' => $cat_id,
                    'type_ressource' => 'project',
                    'owner_id' => $user_id
                ]);
            }
        }
        return redirect()->back()->with(["success" => "Le projet à bien été créé"]);
    }

    public function RemoveTaskFromProject(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required", "integer"],
            "project_id" => ["required", "integer"]
        ]);
        $Task_id = $validateData["task_id"];
        $Project_id = $validateData["project_id"];
        $user_id = Auth::user()->id;

        $projet = Projet::find($Project_id);

        if (!$projet) return redirect()->route("home")->with("failure", "Le projet auxquel vous tentez d'accéder n'existe pas");

        $projet->tasks()->detach($Task_id);

        $task = Task::find($Task_id);
        LogsController::deleteTask($user_id,$Task_id,$task->task_name,"SUCCESS");
        $task->delete();
        return redirect()->back()->with(["success" => "Tâche supprimé du projet avec succès"]);
    }

    // TaskTODO -> TaskDone
    public function CheckTaskTODO(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required", "integer"]
        ]);

        $user_id = Auth::user()->id;

        $id = $validateData["task_id"];
        $task = Task::find($id);

        if (!$task) return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");

        $task->is_finish = true;
        $task->save();
        LogsController::saveTask($user_id,"on","SUCCESS",$task->getKey());
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

        $user_id = Auth::user()->id;

        if (!$task) return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");

        $task->is_finish = false;
        $task->save();
        LogsController::saveTask($user_id,"off","SUCCESS",$task->getKey());
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

        // insideprojet::where("projet_id", $project_id)->delete();
        $inside = insideprojet::where("projet_id", $project_id)->get();

        // Suppression des tâches lié aux projets
        foreach ($inside as $i){
            insideprojet::where([
                "projet_id" => $project_id,
                "task_id" => $i->task_id
            ])->delete();
            Task::find($i->task_id)->delete();

        }



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
        LogsController::DeleteProject($user_id,$project_id,$projet->name);
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
            LogsController::UncheckProject($user_id,$project_id,$projet->name);
        } else {
            $projet->is_finish = true;
            LogsController::CheckProject($user_id,$project_id,$projet->name);
        }


        $projet->save();

        return redirect()->back()->with("success", "Le projet " . $name . $msg);


    }


    public function AddExistingTaskToProject(Request $request)
    {

        $task_id = $request->get("task_id");
        $project_id = $request->get("project_id");

        $user_id = Auth::user()->id;
        $task = Task::find($task_id);

        if(!$task) return redirect()->route("home")->with("failure","La tâche que vous souhaitez ajouter n'existe pas");

        if ($task->owner_id != $user_id) return redirect()->route("home")->with("failure","Vous n'avez pas les autorisations");



        $inside = new insideprojet();
        $inside->task_id = $task_id;
        $inside->projet_id = $project_id;

        // Get la nouvelle pos  = max + 1
        $max = InsideProjet::where('projet_id', $project_id)
            ->max('pos');
        $inside->pos = $max + 1;
        $inside->save();

        CategorieController::HeritageCategorieProjectToTask($task_id,$project_id);

        return redirect()->back()->with(["success" => "La tâche a bien été ajouter à votre projet"]);

    }


    public function UnlinkTaskFromProject(Request $request)
    {
       $task_id  = $request->get("task_id");
       $project_id = $request->get("project_id");
       $user_id = Auth::user()->id;

       $task = Task::find($task_id);

        if(!$task) return redirect()->route("home")->with("failure","La tâche que vous souhaitez ajouter n'existe pas");

        if ($task->owner_id != $user_id) return redirect()->route("home")->with("failure","Vous n'avez pas les autorisations");

        $inside = insideprojet::where([
            "task_id" => $task_id,
            "projet_id" => $project_id
        ])->delete();

        return  redirect()->back()->with("success","Tâche dissocié du projet avec succès");

    }

    public function updateProject(Request $request)
    {
        $validateData = $request->validate([
            'projet_id' => ['required', 'integer'],
            'projet_name' => ['required', 'string', 'max:250'],
            'categories' => ['nullable', 'array']
        ]);
        $user_id = Auth::user()->id;
        $projet = Projet::find($validateData['projet_id']);
        if (!$projet || $projet->owner_id != $user_id) {
            return redirect()->back()->with('failure', "Projet introuvable ou non autorisé");
        }
        $projet->name = $validateData['projet_name'];
        $projet->save();
        // Mise à jour des catégories
        possede_categorie::where([
            ['ressource_id', $projet->id],
            ['type_ressource', 'project'],
            ['owner_id', $user_id]
        ])->delete();
        if (!empty($validateData['categories'])) {
            foreach ($validateData['categories'] as $cat_id) {
                possede_categorie::create([
                    'ressource_id' => $projet->id,
                    'categorie_id' => $cat_id,
                    'type_ressource' => 'project',
                    'owner_id' => $user_id
                ]);
            }
        }
        return redirect()->back()->with('success', 'Projet mis à jour avec succès');
    }

}
