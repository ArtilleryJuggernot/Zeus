<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\insideprojet;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class TaskController extends Controller
{
    public function OverView()
    {

        $user_id = Auth::user()->id; // Récupérer l'utilisateur actuel
        //DB::enableQueryLog();
        $task_list =    Task::where("owner_id",$user_id)->get();
        //dd($task_list);
        //dd(DB::getQueryLog());
        return view("task.TaskOverview",
        [
            "task_list" => $task_list
        ]);
    }

    public function View(int $id)
    {

        $task = Task::find($id);

        if(!$task)
            return redirect()->route("home")->with("failure","La tache que vous tentez de modifier n'existe pas");


        return view("task.TaskView",[
            "task" => $task
        ]);
    }

    public function Save(Request $request)
    {
        $output = new ConsoleOutput();
        $output->writeln("Je suis dans save consoleOutput");

        if($request->has("btn_is_finished")){
            $output->writeln("BTN IS FINISHED DEFINIT. LA VALEUR EST : ");
            $output->writeln( (string) $request->get("btn_is_finished"));
        }

        $output->writeln("Debut de la verification");
        $validateData = $request->validate([
                "content" => ["required","string"],
                "user_id" => ["required","integer"],
                "task_id" => ["required","integer"],
                "btn_is_finished" => ["required","in:on,off"]
            ]);

        $output->writeln("Fin de la verification");


        $content = $validateData['content'];
        $user_id = $validateData["user_id"];
        $note_id = $validateData["task_id"];



        if ($user_id == Auth::user()->id) {
            $task = Task::find($note_id);

            if(!$task)
                return redirect()->route("home")->with("failure","La tache que vous tentez de modifier n'existe pas");


            if ($task->owner_id == Auth::user()->id) { // TODO : Système d'autorisation

                $output->writeln("Assignation description");
                $task->description = $content;

                if($validateData["btn_is_finished"] == "on"){
                    $output->writeln("Je suis dans la condition");
                    $task->is_finish = true;
                    $task->finished_at = Carbon::now();
                }
                else{
                    $output->writeln("Je suis dans le else");
                    $task->is_finish = false;
                }


                $task->save();
                return response()->json(['success' => true]);
            }
            return response()->with("failure",false);

        }
    }

    public function Store(Request $request)
    {
        //dd($request);

        if($request->has('is_due')){
            $validateData = $request->validate([
                "tache_name" => ["required","string","max:250"],
                "is_due" => ["nullable","in:on,off"],
                "dt_input" => ["nullable","date"]
            ]);
        }
        else{
            $validateData = $request->validate([
                "tache_name" => ["required","string","max:250"],
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
        return redirect()->back()->with(["success" => "La tâche à bien été créer"]);
    }

    public function Delete(Request $request)
    {
        $validateData = $request->validate([
            "id" => ["required","integer"]
        ]);
        $id = $validateData["id"];
        $task = Task::find($id);

        if(!$task)
            return redirect()->route("home")->with("failure","La tache que vous tentez de modifier n'existe pas");


        insideprojet::where("task_id",$id)->delete();
        $task->delete();
        return redirect()->back()->with(["success" => "Tâche supprimé avec succès"]);
    }

}
