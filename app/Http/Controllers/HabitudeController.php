<?php

namespace App\Http\Controllers;

use App\Models\habit_possede;
use App\Models\HabitPossede;
use App\Models\Habitude;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitudeController extends Controller
{
    public function Overview()
    {
        $user_id = Auth::user()->id;
        $habitudes_enable = Habitude::where([
            ['user_id', $user_id],
            ['is_enable',1]
        ])->get();

        $habitude_not = Habitude::where([
            ['user_id', $user_id],
            ['is_enable',0]
        ])->get();


        return view("modules.habitude.HabitudeOverview",
        ["habitudes" => $habitudes_enable],
            ["habitudes_not" => $habitude_not]


        );
    }


    public function View(int $id)
    {
        $user_id = Auth::user()->id;

        $habitude = Habitude::find($id);
        $habitude_id = $habitude->id;

        $habits_possede = habit_possede::where("habit_id",$habitude_id)->get();

        $arr = [];
        foreach ($habits_possede as $habits){
            $arr[$habits->day_id - 1] = [
                "start" => $habits->start,
                "stop" => $habits->stop
            ];
        }


        return view("modules.habitude.HabitudeView",
        [
         "habitude" => $habitude,
            "habits_possede" => $arr
        ]);
    }


    public function Store(Request $request)
    {
        // Validation des données
        $user_id = Auth::user()->id;

      $validated = $request->validate([
          'habitude_name' => 'required|string|max:255',
          'day_0' => 'required|boolean',
          'lundi-start' => 'required_if:day_0,1|',
          'lundi-stop' => 'required_if:day_0,1||after:lundi-start',
           'day_1' => 'required|boolean',
          'mardi-start' => 'required_if:day_1,1|',
          'mardi-stop' => 'required_if:day_1,1||after:mardi-start',
          'day_2' => 'required|boolean',
          'mercredi-start' => 'required_if:day_2,1|',
          'mercredi-stop' => 'required_if:day_2,1||after:mercredi-start',
          'day_3' => 'required|boolean',
          'jeudi-start' => 'required_if:day_3,1|',
          'jeudi-stop' => 'required_if:day_3,1||after:jeudi-start',
          'day_4' => 'required|boolean',
          'vendredi-start' => 'required_if:day_4,1|',
          'vendredi-stop' => 'required_if:day_4,1||after:vendredi-start',
          'day_5' => 'required|boolean',
          'samedi-start' => 'required_if:day_5,1|',
          'samedi-stop' => 'required_if:day_5,1||after:samedi-start',
          'day_6' => 'required|boolean',
          'dimanche-start' => 'required_if:day_6,1|',
          'dimanche-stop' => 'required_if:day_6,1||after:dimanche-start',

      ]);

        // Créer une tâche

        $task = new Task();
        $task->task_name = "[HABITUDE] - " . $validated["habitude_name"];
        $task->owner_id = $user_id;
        $task->type = "habitude";
        $task->description = "# " .  $task->task_name;
        $task->save();
        $task_id = $task->getKey();

        // Création de l'Habitude

        $habitude = new Habitude();
        $habitude->user_id = $user_id;
        $habitude->task_id = $task_id;
        $habitude->name = $validated["habitude_name"];
        $habitude->save();
        $habitude_id = $habitude->getKey();


      // Créer les habitudes lié au deltatime

      for ($i = 0; $i < 7; $i++){
          // Jour activé
          $arr = ["lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche"];
          if($validated["day_" . $i] == "1"){
              $habit_possede = new habit_possede();
              $habit_possede->habit_id = $habitude_id;
              $habit_possede->day_id = $i + 1; // la table SQL commence à 1
              $habit_possede->start = $validated[$arr[$i] . "-start"];
              $habit_possede->stop = $validated[$arr[$i] . "-stop"];
              $habit_possede->save();
          }
      }
      return redirect()->back()->with("success","La nouvelle habitude " . $validated["habitude_name"] . " a bien été créer");



        //strtotime();
    }


    public function Delete(Request $request)
    {

    }

    public function Toggle(Request $request)
    {

    }

    public function Update (Request $request)
    {
        $validated = $request->validate([
            'user_id' => "required|int",
            'habit_id' => "required|int",
            'day_0' => 'required|boolean',
            'lundi-start' => 'required_if:day_0,1|',
            'lundi-stop' => 'required_if:day_0,1||after:lundi-start',
            'day_1' => 'required|boolean',
            'mardi-start' => 'required_if:day_1,1|',
            'mardi-stop' => 'required_if:day_1,1||after:mardi-start',
            'day_2' => 'required|boolean',
            'mercredi-start' => 'required_if:day_2,1|',
            'mercredi-stop' => 'required_if:day_2,1||after:mercredi-start',
            'day_3' => 'required|boolean',
            'jeudi-start' => 'required_if:day_3,1|',
            'jeudi-stop' => 'required_if:day_3,1||after:jeudi-start',
            'day_4' => 'required|boolean',
            'vendredi-start' => 'required_if:day_4,1|',
            'vendredi-stop' => 'required_if:day_4,1||after:vendredi-start',
            'day_5' => 'required|boolean',
            'samedi-start' => 'required_if:day_5,1|',
            'samedi-stop' => 'required_if:day_5,1||after:samedi-start',
            'day_6' => 'required|boolean',
            'dimanche-start' => 'required_if:day_6,1|',
            'dimanche-stop' => 'required_if:day_6,1||after:dimanche-start',

        ]);

        // Verification basics

        $user_id = $validated["user_id"];
        if($user_id != Auth::user()->id) return redirect()->back()->with("failure","Vous n'avez pas la permission");

        $habitude = Habitude::find($validated["habit_id"]);

        if(!$habitude) return redirect()->route("home")->with("failure","L'habitude que vous cherchez n'existe pas");

        // Process
        $arr = ["lundi","mardi","mercredi","jeudi","vendredi","samedi","dimanche"];
        for ($i = 0; $i < 7; $i++){
            // Jour activé
            if($validated["day_" . $i] == "1"){

                $habit_possede = habit_possede::where([
                    ["habit_id",$validated["habit_id"] ],
                    ["day_id",$i + 1]
                ])->delete();



                $new_habit = new habit_possede();
                $new_habit->habit_id = $validated["habit_id"];
                $new_habit->day_id = $i + 1; // la table SQL commence à 1
                $new_habit->start = $validated[$arr[$i] . "-start"];
                $new_habit->stop = $validated[$arr[$i] . "-stop"];
                $new_habit->save();



            }

            // On souhaite supprimé l'habitude pour la journée
            else{
                $habit = habit_possede::where([
                    ["habit_id",$validated["habit_id"] ],
                    ["day_id",$i + 1]
                ])->delete();


            }
        }

        return redirect()->back()->with("success","L'habitude a bien été mis à jour !");

    }
}
