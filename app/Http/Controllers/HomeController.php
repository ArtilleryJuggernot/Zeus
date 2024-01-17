<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    function HomeView()
    {
        $user = Auth::user();

        // Tache

        // Tache à faire avec date limite en cours
        $tachesTimed = Task::where([
            ["owner_id",$user->id],
            ["due_date",">",Carbon::today()],
            ["is_finish",0]
        ])->get();

        // Tache à faire passé
        $tachesPasse = Task::where([
            ["owner_id",$user->id],
            ["due_date","<",Carbon::today()],
            ["is_finish",0]
        ])->get();



        return view("home",[
            "user" => $user,
            "tachesTimed" => $tachesTimed,
            "tachePasse" => $tachesPasse
        ]);
    }

    function AboutView(){
        return view("about.about");
    }
}
