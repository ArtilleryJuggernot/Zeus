<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function OverView()
    {

        $user_id = Auth::user()->id; // Récupérer l'utilisateur actuel
        DB::enableQueryLog();
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
        return view("task.TaskView",[
            "task" => $task
        ]);
    }

    public function Add()
    {
        return view("task.AddTask");
    }

    public function Save(Request $request)
    {
        $content = $request->input('content');
        $user_id = $request->input("user_id");
        $note_id = $request->input("task_id");

        if ($user_id == Auth::user()->id) {
            $task = Task::find($note_id);

            if ($task->owner_id == Auth::user()->id) {
                $task->description = $content;

                if($request->input("btn_is_finished") == "on"){
                    $task->is_finish = true;
                    $task->finished_at = Carbon::now();
                }
                else{
                    $task->is_finish = false;
                }
                $task->save();
                return response()->json(['success' => true]);
            }

        }
    }

    public function Store(Request $request)
    {
        $name = $request->get("tache_name");
        $task = new Task();
        $task->task_name = $name;
        $task->owner_id = Auth::user()->id;

        if($request->get("is_due") == "on"){
            $task->due_date = $request->get("dt_input"); // Date limite
        }
        $task->save();
        return redirect()->back()->with(["success" => "La tâche à bien été créer"]);
    }

    public function Delete(Request $request)
    {
        $id = $request->get("id");
        $task = Task::find($id);
        $task->delete();
        return redirect()->back()->with(["success" => "Tâche supprimé avec succès"]);
    }

}
