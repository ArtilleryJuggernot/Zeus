<?php

namespace App\Http\Controllers;

use App\Models\logs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{


    // Tache
    public static function saveTask($user_id,$btn_finish,$action_status,$task_id)
    {

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>ENTREE DANS le LOGS SAVE TASK</info>");

        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") SAVE TASK";
        $logs->ressource_id = $task_id;
        $logs->ressource_type = "task";
        $logs->content  = "Contenu mis à jour pour la tâche n°" . $task_id
            . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ").";



        if ($btn_finish == "on") $logs->content .= " Mis à jour : Tâche finis";
        else $logs->content .= " Mis à jour : Tâche non finis";
         $logs->save();

    }
    public static function createTask($user_id,$task_id,$task_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") CREATE TASK";
        $logs->ressource_id = $task_id;
        $logs->ressource_type = "task";
        $logs->content = "Création d'une tâche avec l'id n°" . $task_id
        . " et le nom : " . $task_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ").";

        $logs->save();

    }
    public static function deleteTask($user_id,$task_id,$task_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") DELETE TASK";
        $logs->ressource_id = $task_id;
        $logs->ressource_type = "task";
        $logs->content = "Suppression d'une tâche avec l'id n°" . $task_id
            . " et le nom : " . $task_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ").";

        $logs->save();
    }


    // Catégorie
    public static function createCategory($user_id,$category_id,$category_name,)
    {

    }

    public static function deleteCategory()
    {

    }
    public static function AddCategoryToRessources()
    {

    }

}
