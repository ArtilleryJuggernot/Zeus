<?php

namespace App\Http\Controllers;


use App\Models\Task;
use App\Models\task_priorities;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function HomeView()
    {
        $user = Auth::user();



        //$this::attribuerCategoriesAuxRessources();

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


        // Habitude

        $habitudes = Task::where([
            ["type","habitude"],
            ["is_finish",0]
        ])->get();



        return view("home",[
            "user" => $user,
            "tachesTimed" => $tachesTimed,
            "tachePasse" => $tachesPasse,
            "task_priority" => $task_priority,
            "habitudes" => $habitudes
        ]);
    }

    function AboutView(){
        return view("about.about");
    }





}
