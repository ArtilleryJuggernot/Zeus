<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Categorie;
use App\Models\Folder;
use App\Models\insideprojet;
use App\Models\logs;
use App\Models\possede_categorie;
use App\Models\Projet;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class TaskController extends Controller
{
    public function OverView(Request $request)
    {
        $user_id = Auth::user()->id;

        $task_list_finish = $this->getTaskListWithCategories($user_id, 1);
        $task_list_unfinish = $this->getTaskListWithCategories($user_id, 0);

        return view("task.TaskOverview", [
            "task_list_finish" => $task_list_finish,
            "task_list_unfinish" => $task_list_unfinish,
        ]);
    }

    protected function getTaskListWithCategories($user_id, $is_finish)
    {
        $tasks = Task::where([
            ["owner_id", $user_id],
            ["is_finish", $is_finish],
        ])
            ->whereDoesntHave('projects')
            ->get();

        foreach ($tasks as $task) {
            $task->categories = $this->getTaskCategories($task->task_id, $user_id);
        }

        return $tasks;
    }



    protected function getTaskCategories($taskId, $user_id)
    {
        $resourceCategories = possede_categorie::where('ressource_id', $taskId)
            ->where('type_ressource', 'task')
            ->where('owner_id', $user_id)
            ->get();


        return $resourceCategories;
    }


    public function OverviewTaskProject(Request $request)
    {
        $user_id = Auth::user()->id; // Récupérer l'utilisateur actuel

        $task_list_finish =
            Task::with('projects')->where([
            ["owner_id", $user_id],
            ["is_finish",1]
        ])
            ->whereHas('projects')
            ->get();


        $task_list_unfinish = Task::with('projects')->where([
            ["owner_id", $user_id],
            ["is_finish",0]
        ])
            ->whereHas('projects')
            ->get();

        return view("task.TaskOverviewProject",
            [
                "task_list_finish" => $task_list_finish,
                "task_list_unfinish" => $task_list_unfinish,
            ]);
    }

    public function View(int $id)
    {

        $task = Task::find($id);
        $user_id = Auth::user()->id;

        if (!$task) return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");

        $linkedProjects = $task->projects;
        $availableProjects = $task->availableProjects();

        // Autorisation par visualisation Projet

        // task_id

        $inside_list = insideprojet::where("task_id", $id)->get(); // Liste des projets qui possède la tâche
        $perm_user = 0;
        if ($inside_list) {
            foreach ($inside_list as $projet) {
                $usersPermissionsOnNote = Acces::getUsersPermissionsOnProject($projet->projet_id);
                //dd($usersPermissionsOnNote);
                //dd($user_id)
                $auto_spe_note_other = false;
                if (Projet::find($projet->projet_id)->owner_id == $user_id) {
                    $auto_spe_note_other = true;
                    // Permission propriétaire
                    $perm_user = "F";
                }

                $autorisation_partage_p_rec = false;
                foreach ($usersPermissionsOnNote as $acces) {
                    if ($acces->dest_id == $user_id) {
                        $autorisation_partage_p_rec = true;
                        $perm_user = $acces;
                        break;
                    }
                }
            }
        }

        //dd($autorisation_partage_p_rec);

        //dd($perm_user);
        // Le droit donne par le partage  par tache et prioritaire par rapport au projet
        $usersPermissionsOnNote = Acces::getUsersPermissionsOnTask($id);
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces) {
            if ($acces->dest_id == $user_id) {
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }


        //$output->writeln();
        //dd($autorisation_partage); // false car il n'y a pas de partage direct de la tâche
        //dd($autorisation_partage_p_rec); // false aussi car il le propriétaire n'a une une autorisation pour lui même
        //dd($auto_spe_note_other);
        if (($user_id != $task->owner_id && !$autorisation_partage) && !$autorisation_partage_p_rec && !$auto_spe_note_other) //
            return redirect()->route("home")->with("failure", "Vous n'avez pas l'autorisation de voir cette ressource2");


        $resourceCategories = possede_categorie::where('ressource_id', $id)
            ->where('type_ressource', "task")
            ->where('owner_id', $user_id)->get();

// Obtenez toutes les catégories en utilisant le modèle Categorie
        $allCategories = Categorie::all()->where("owner_id", $user_id);

        //dd($allCategories);

// Obtenez les catégories possédées par la ressource
        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();

// Séparez les catégories possédées et non possédées
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();
        $notOwnedCategories = $allCategories->whereNotIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();


        return view("task.TaskView", [
            "task" => $task,
            "usersPermissionList" => $usersPermissionsOnNote,
            "perm_user" => $perm_user,       // TODO : Remplacer par $accesRecursif quand la collab par projet sera là avec la fonction qui va bien cf NoteController
            "ressourceCategories" => $resourceCategories,
            "ownedCategories" => $ownedCategories,
            "notOwnedCategories" => $notOwnedCategories,
            "linkedProjects" => $linkedProjects,
            "availableProjects" => $availableProjects
        ]);
    }

    public function Save(Request $request)
    {

        $validateData = $request->validate([
            "content" => ["required", "string"],
            "user_id" => ["required", "integer"],
            "task_id" => ["required", "integer"],
            "perm" => ["required", "in:RO,RW,F"],
            "btn_is_finished" => ["required", "in:on,off"]
        ]);




        $content = $validateData['content'];
        $user_id = $validateData["user_id"];
        $note_id = $validateData["task_id"];
        $perm = $validateData["perm"];
        $btn_finish = $validateData["btn_is_finished"];


        $task = Task::find($note_id);
        if (!$task) return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");





        $perm_test = $perm == "RW" || $perm == "F";
        if ($task->owner_id == Auth::user()->id || $perm_test) { // TODO : Système d'autorisation
            $task->description = $content;
            if ($validateData["btn_is_finished"] == "on") {
                $task->is_finish = 1;
                $task->finished_at = Carbon::now();  //kansi uml
            } else {
                $task->is_finish = 0;
            }

            $task->save();

            LogsController::saveTask($user_id,$btn_finish,"SUCCESS",$note_id);
            return response()->json(['success' => true]);
        }
        LogsController::saveTask($user_id,$btn_finish,"FAILURE",$note_id);
        return response()->with("failure", false);

    }

    public function Store(Request $request)
    {
        //dd($request);

        if ($request->has('is_due')) {
            $validateData = $request->validate([
                "tache_name" => ["required", "string", "max:250"],
                "is_due" => ["nullable", "in:on,off"],
                "dt_input" => ["nullable", "date"]
            ]);
        } else {
            $validateData = $request->validate([
                "tache_name" => ["required", "string", "max:250"],
            ]);
        }


        $name = $validateData["tache_name"];
        $task = new Task();
        $task->task_name = $name;
        $task->owner_id = Auth::user()->id;

        if ($request->has("is_due") && $validateData["is_due"] == "on") {
            $task->due_date = $validateData["dt_input"]; // Date limite
        }
        $task->description = "# " .  $name;
        $task->save();
        LogsController::createTask(Auth::user()->id,$task->id,$name,"SUCCESS");

        return redirect()->back()->with(["success" => "La tâche à bien été créer"]);
    }

    public function Delete(Request $request)
    {
        $validateData = $request->validate([
            "id" => ["required", "integer"]
        ]);
        $id = $validateData["id"];
        $task = Task::find($id);

        if (!$task) {
            LogsController::deleteTask(Auth::user()->id,$id,"null","FAILURE");
            return redirect()->route("home")->with("failure", "La tache que vous tentez de modifier n'existe pas");
        }


        // Supprimer les droits associés à une note

        Acces::where([
            ["ressource_id", $id],
            ["type", "task"],
        ])->delete();


        // Supprimer les catégories associés à la note

        possede_categorie::where([
            ["ressource_id", $id],
            ["type_ressource", "task"]
        ])->delete();


        LogsController::deleteTask(Auth::user()->id,$id,$task->task_name,"SUCCESS");
        insideprojet::where("task_id", $id)->delete();
        $task->delete();
        return redirect()->back()->with(["success" => "Tâche supprimé avec succès"]);
    }

}
