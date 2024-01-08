<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\insideprojet;
use App\Models\Projet;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetController extends Controller
{
    public function Overview()
    {
        $user_id = Auth::user()->id;
        $userProjets = Projet::where("owner_id","=",$user_id)->get();
        return view("projet.ProjetOverview",
        [
            "userProjets" => $userProjets
        ]);
    }

    public function View(int $id)
    {
        $user_id = Auth::user()->id;
        $projet = Projet::with('tasks')->find($id);

        if(!$projet)
            return redirect()->route("home")->with("failure","Le projet que vous souhaitez voir n'existe pas");

        $taskFinish = $projet->tasks->where("is_finish", 1);
        $taskTODO = $projet->tasks->where("is_finish", 0);

        foreach ($taskTODO as $task){
            $task->pos = $task->getPositionForProject($id);
        }

        if((count($taskTODO)  + count($taskFinish)) == 0) $progression = 100; // Division par 0

        else $progression = (count($taskFinish) / (count($taskTODO)  + count($taskFinish))) * 100 ;

        //dd($tasks);


        $usersPermissionsOnNote = Acces::getUsersPermissionsOnProject($id);
        $perm_user = 0;
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces){
            if($acces->dest_id == $user_id){
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }
        $usersPermissionsOnNote = Acces::getUsersPermissionsOnProject($id);
        $perm_user = 0;
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces){
            if($acces->dest_id == $user_id){
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }


        if($projet->owner_id == $user_id || $autorisation_partage){ // TODO : Modif pour collab
            return view("projet.ProjetView",
            [
                "projet" => $projet,
                "taskFinish" => $taskFinish,
                "taskTODO" => $taskTODO,
                "progression" => $progression,
                "usersPermissionList" => $usersPermissionsOnNote,
                "perm_user" => $perm_user
            ]);
        }




        return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à voir cette ressource");
    }


    public function AddTask(Request $request)
    {
        //dd($request);
        if($request->has('is_due')){
            $validateData = $request->validate([
                "tache_name" => ["required","string","max:250"],
                "is_due" => ["nullable","in:on,off"],
                "dt_input" => ["nullable","date"],
                "project_id" => ["required","integer"]
            ]);
        }
        else{
            $validateData = $request->validate([
                "tache_name" => ["required","string","max:250"],
                "project_id" => ["required","integer"]
            ]);
        }


        $name = $validateData["tache_name"];
        $task = new Task();
        $task->task_name = $name;
        $task->owner_id = Auth::user()->id;

        if($request->has("is_due") && $validateData["is_due"] == "on"){
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
        $inside->pos = $max +1;
        $inside->save();

        return redirect()->back()->with(["success" => "La tâche à bien été créer"]);

    }

    public function Store(Request $request)
    {
        //dd($request);
        $validateData = $request->validate([
            "projet_name" => ["required","string","max:250"]
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
            "task_id" => ["required","integer"],
            "project_id" => ["required","integer"]
        ]);
        $Task_id = $validateData["task_id"];
        $Project_id = $validateData["project_id"];

        $projet = Projet::find($Project_id);

        if(!$projet) return redirect()->route("home")->with("failure","Le projet auxquel vous tentez d'accéder n'existe pas");

        $projet->tasks()->detach($Task_id);
        Task::find($Task_id)->delete();
        return redirect()->back()->with(["success" => "Tâche supprimé du projet avec succès"]);
    }

    // TaskTODO -> TaskDone
    public function CheckTaskTODO(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required","integer"]
        ]);
        $id = $validateData["task_id"];
        $task = Task::find($id);

        if(!$task) return redirect()->route("home")->with("failure","La tache que vous tentez de modifier n'existe pas");

        $task->is_finish = true;
        $task->save();
        return redirect()->back()->with(["success" => "Tâche du projet validée avec succès"]);
    }

    // TaskDone -> TaskTODO
    public function UncheckTaskDone(Request $request)
    {
        $validateData = $request->validate([
            "task_id" => ["required","integer"]
        ]);
        $id = $validateData["task_id"];
        $task = Task::find($id);

        if(!$task) return redirect()->route("home")->with("failure","La tache que vous tentez de modifier n'existe pas");

        $task->is_finish = false;
        $task->save();
        return redirect()->back()->with(["success" => "Tâche remise dans le projet avec succès"]);
    }
}
