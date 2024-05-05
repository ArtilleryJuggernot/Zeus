<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\stats;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatsLoggerController extends Controller
{
    public static function CheckTask($user_id,$task_id): void
    {


        // Création de la stat
        $stat = new stats();
        $stat->user_id = $user_id;
        $stat->ressource_id = $task_id;
        $stat->ressource_type = "task";
        $stat->action = "CHECK TASK";
        $stat->created_at = Carbon::now();
        $stat->save();
    }

    public static function UncheckTask($user_id,$task_id): void
    {
        // Vérification si il n'y a pas une stats avec la tache déjà check
        $alreadyCheckTask = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $task_id,
            "ressource_type" => "task",
            "action" => "CHECK TASK"
        ])->first();

        // la supprimer si elle existe
        if($alreadyCheckTask) $alreadyCheckTask->delete();

    }

    public static function CreateTask($user_id,$task_id) : void
    {
        // Vérification d'un doublon

        $alreadyStat = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $task_id,
            "ressource_type" => "task",
            "action" => "CREATE TASK"
        ])->first();

        if(!$alreadyStat){
            $stat = new stats();
            $stat->user_id = $user_id;
            $stat->ressource_id = $task_id;
            $stat->ressource_type = "task";
            $stat->action = "CREATE TASK";
            $stat->created_at = Carbon::now();
            $stat->save();
        }
    }

    public static function DeleteTask($user_id,$task_id): void
    {
        // On supprime uniquement la stat "CREATE TASK" associé à la tâche si cette dernière n'est pas terminer (jamais commencer, comme si elle n'avait jamais exister)

        $alreayCreatedTask = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $task_id,
            "ressource_type" => "task",
            "action" => "CREATE TASK"
        ])->first();

        if($alreayCreatedTask){
            $task = Task::find($task_id);
            if (!$task->is_finish) $alreayCreatedTask->delete();
        }
    }

    public static function CreateCategory($user_id,$category_id): void
    {

        $alreadyStat = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $category_id,
            "ressource_type" => "category",
            "action" => "CREATE CATEGORY"
        ])->first();

        if(!$alreadyStat){
            $stat = new stats();
            $stat->user_id = $user_id;
            $stat->ressource_id = $category_id;
            $stat->ressource_type = "category";
            $stat->action = "CREATE CATEGORY";
            $stat->created_at = Carbon::now();
            $stat->save();
        }


    }

    public static function DeleteCategory($user_id,$category_id): void
    {
// Vérification si il n'y a pas une stats avec la catégorie créer
        $alreadyCreatedCategory = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $category_id,
            "ressource_type" => "category",
            "action" => "CREATE CATEGORY"
        ])->first();

        // la supprimer si elle existe
        if($alreadyCreatedCategory) $alreadyCreatedCategory->delete();
    }

    public static function CreateNote($user_id,$note_id): void
    {

        $alreadyStat = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $note_id,
            "ressource_type" => "note",
            "action" => "CREATE NOTE"
        ])->first();

        if(!$alreadyStat){
            $stat = new stats();
            $stat->user_id = $user_id;
            $stat->ressource_id = $note_id;
            $stat->ressource_type = "note";
            $stat->action = "CREATE NOTE";
            $stat->created_at = Carbon::now();
            $stat->save();
        }


    }

    public static function DeleteNote($user_id,$note_id): void
    {
        $alreadyCreatedNote = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $note_id,
            "ressource_type" => "note",
            "action" => "CREATE NOTE"
        ])->first();

        // la supprimer si elle existe
        if($alreadyCreatedNote) $alreadyCreatedNote->delete();
    }

    public static function CreateFolder($user_id,$folderID)
    {

        $alreadyStat = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $folderID,
            "ressource_type" => "folder",
            "action" => "CREATE FOLDER"
        ])->first();

        if (!$alreadyStat){
            $stat = new stats();
            $stat->user_id = $user_id;
            $stat->ressource_id = $folderID;
            $stat->ressource_type = "folder";
            $stat->action = "CREATE FOLDER";
            $stat->created_at = Carbon::now();
            $stat->save();
        }
    }

    public static function DeleteFolder($user_id,$folderID)
    {
        $alreadyCreatedFolder = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $folderID,
            "ressource_type" => "folder",
            "action" => "CREATE FOLDER"
        ])->first();

        // la supprimer si elle existe
        if($alreadyCreatedFolder) $alreadyCreatedFolder->delete();
    }

    public static function CheckProject($user_id,$project_id)
    {
        $stat = new stats();
        $stat->user_id = $user_id;
        $stat->ressource_id = $project_id;
        $stat->ressource_type = "project";
        $stat->action = "CHECK PROJECT";
        $stat->created_at = Carbon::now();
        $stat->save();
    }

    public static function UncheckProject($user_id,$project_id)
    {
        $alreadyCheckProject = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $project_id,
            "ressource_type" => "project",
            "action" => "CHECK PROJECT"
        ])->first();

        // la supprimer si elle existe
        if($alreadyCheckProject) $alreadyCheckProject->delete();
    }

    public static function CreateProject($user_id,$project_id)
    {

        $alreadyStat = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $project_id,
            "ressource_type" => "project",
            "action" => "CREATE PROJECT"
        ])->first();

        if (!$alreadyStat){
            $stat = new stats();
            $stat->user_id = $user_id;
            $stat->ressource_id = $project_id;
            $stat->ressource_type = "project";
            $stat->action = "CREATE PROJECT";
            $stat->created_at = Carbon::now();
            $stat->save();
        }
    }

    public static function DeleteProject($user_id,$project_id)
    {
        $alreayCreatedProject = stats::where([
            "user_id" => $user_id,
            "ressource_id" => $project_id,
            "ressource_type" => "project",
            "action" => "CREATE PROJECT"
        ])->first();

        if($alreayCreatedProject){
            $task = Projet::find($project_id);
            if (!$task->is_finish) $alreayCreatedProject->delete();
        }
    }
}
