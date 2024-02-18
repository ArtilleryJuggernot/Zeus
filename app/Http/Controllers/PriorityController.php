<?php

namespace App\Http\Controllers;

use App\Models\possede_categorie;
use App\Models\Task;
use App\Models\task_priorities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriorityController extends Controller
{
    public function PriorityChange(Request $request)
    {
        $priorityValue = $request->input('priority');
        $taskId = $request->input('id');
        $user_id = Auth::user()->id;

        // La tache existe
        $task = Task::find($taskId);
        if(!$task) return redirect()->route("home")->with("failure","La tâche n'a pas pu être trouvé");


        // Seul le propriétaire de la tâche peut modifier la priorité
        if ($task->owner_id != $user_id) return redirect()->route("home")->with("failure","Seul le propriétaire de la tâche peut définir sa priorité");

        // Vérification si une priorité n'existe pas déjà

        $exist = task_priorities::where([
            "user_id" => $user_id,
            "task_id" => $taskId
        ])->first();

        if ($exist){

            if($priorityValue != ""){
                $exist->priority = $priorityValue;
                $exist->save();
            }
            else{
                $exist->delete();
            }

        }
        else
        {
            $priority = new task_priorities();
            $priority->user_id = $user_id;
            $priority->task_id = $taskId;
            $priority->priority = $priorityValue;
            $priority->save();
        }

        return redirect()->back()->with("success","La priorité à bien était changer");
    }


    public static function sortTasksByPriority($tasks)
    {
        // Définir l'ordre de priorité
        $priorityOrder = ['Urgence', 'Grande priorité', 'Prioritaire'];

        // Trier les tâches en fonction de leur priorité
        $sortedTasks = $tasks->sortBy(function ($task) use ($priorityOrder) {
            // Récupérer la priorité de la tâche
            $priority = $task->priority;

            // Trouver l'indice de la priorité dans l'ordre défini
            $priorityIndex = array_search($priority, $priorityOrder);

            // Si la priorité n'est pas dans l'ordre défini, la placer à la fin
            return $priorityIndex === false ? count($priorityOrder) : $priorityIndex;
        });

        return $sortedTasks;
    }
}
