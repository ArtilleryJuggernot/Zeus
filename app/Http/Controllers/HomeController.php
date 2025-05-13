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


        // Tâches à faire avec date limite en cours
        $tachesTimed  = Task::whereDoesntHave('projects', function($query) {
            $query->where('type', 'livre');
        })->where([
            ["owner_id", $user->id],
            ["due_date", ">", Carbon::today()],
            ["is_finish", 0]
        ])->get();




        // Tâches à faire passées
        $tachesPasse = Task::where([
            ["owner_id",$user->id],
            ["due_date","<",Carbon::today()],
            ["is_finish",0]
        ])->get();


        // Tâches en priorité

        $task_priorities = task_priorities::where('user_id', $user->id)
            ->whereHas('task', function ($query) {
                $query->where('is_finish', false);
            })
            ->get();

        $task_priority = PriorityController::sortTasksByPriority($task_priorities);


        // Habitudes

        $habitudes = Task::where([
            ["type","habitude"],
            ["is_finish",0]
        ])->get();

        // Liste des tâches en cours (hors habitude, hors terminées, hors prioritaires, hors passées, hors tâches à échéance)
        $priority_task_ids = $task_priority->pluck('task_id')->toArray();
        $habitude_ids = $habitudes->pluck('id')->toArray();
        $timed_ids = $tachesTimed->pluck('id')->toArray();
        $passe_ids = $tachesPasse->pluck('id')->toArray();

        // $task_list_unfinish = Task::where('owner_id', $user->id)
        //     ->where('is_finish', 0)
        //     ->where('type', '!=', 'habitude')
        //     ->whereNotIn('id', $priority_task_ids)
        //     ->whereNotIn('id', $habitude_ids)
        //     ->whereNotIn('id', $timed_ids)
        //     ->whereNotIn('id', $passe_ids)
        //     ->get();

        // Toutes les catégories pour l'autocomplétion/édition
        $allCategories = \App\Models\Categorie::all()->map(function($cat) {
            return [
                'category_id' => $cat->category_id,
                'category_name' => $cat->category_name,
                'color' => $cat->color
            ];
        });

        return view("home",[
            "user" => $user,
            "tachesTimed" => $tachesTimed,
            "tachePasse" => $tachesPasse,
            "task_priority" => $task_priority,
            "habitudes" => $habitudes,
            // "task_list_unfinish" => $task_list_unfinish,
            "allCategories" => $allCategories
        ]);
    }

    function AboutView(){
        return view("about.about");
    }





}
