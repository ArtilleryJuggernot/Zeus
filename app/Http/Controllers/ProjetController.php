<?php

namespace App\Http\Controllers;

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
        $taskFinish = $projet->tasks->where("is_finish", 1);
        $taskTODO = $projet->tasks->where("is_finish", 0);

        foreach ($taskTODO as $task){
            $task->pos = $task->getPositionForProject($id);
        }

        $progression = (count($taskFinish) / (count($taskTODO)  + count($taskFinish))) * 100 ;

        //dd($tasks);
        if($projet->owner_id == $user_id){
            return view("projet.ProjetView",
            [
                "projet" => $projet,
                "taskFinish" => $taskFinish,
                "taskTODO" => $taskTODO,
                "progression" => $progression,
            ]);
        }
        return view("home");
    }


    public function AddTask(Request $request)
    {
        //dd($request);

        $name = $request->get("tache_name");
        $task = new Task();
        $task->task_name = $name;
        $task->owner_id = Auth::user()->id;

        if($request->get("is_due") == "on"){
            $task->due_date = $request->get("dt_input"); // Date limite
        }
        $task->save();


        // Inside project save
        $projet_id = $request->get("project_id");

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
        $name = $request->get("projet_name");
        $task = new Projet();
        $task->name = $name;
        $task->owner_id = Auth::user()->id;
        $task->save();
        return redirect()->back()->with(["success" => "Le projet à bien été créer"]);
    }

    public function RemoveTaskFromProject(Request $request)
    {
        $Task_id = $request->get("task_id");
        $Project_id = $request->get("project_id");

        $projet = Projet::find($Project_id);
        $projet->tasks()->detach($Task_id);
        return redirect()->back()->with(["success" => "Tâche supprimé du projet avec succès"]);
    }

    // TaskTODO -> TaskDone
    public function CheckTaskTODO(Request $request)
    {
        $id = $request->get("task_id");
        $task = Task::find($id);
        $task->is_finish = true;
        $task->save();
        return redirect()->back()->with(["success" => "Tâche du projet validée avec succès"]);
    }

    // TaskDone -> TaskTODO
    public function UncheckTaskDone(Request $request)
    {
        $id = $request->get("task_id");
        $task = Task::find($id);
        $task->is_finish = false;
        $task->save();
        return redirect()->back()->with(["success" => "Tâche remise dans le projet avec succès"]);
    }
}
