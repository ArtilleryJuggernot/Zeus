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

        $output->writeln($btn_finish);

        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") SAVE TASK";
        $logs->ressource_id = $task_id;
        $logs->ressource_type = "task";
        $logs->content  = "Contenu mis à jour pour la note n°" . $task_id
            . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ").";

        $output->writeln("AVANT MAPPING BOUTON");

        if ($btn_finish == "on") $logs->content .= " Mis à jour : Tâche finis";
        else $logs->content .= " Mis à jour : Tâche non finis";

        $output->writeln("APRES MAPPING BOUTON");
        $output->writeln($logs->content);
        $output->writeln($logs);


        try{
            $check = $logs->save();
        }
        catch(\Exception $e){
        // do task when error
            $output->writeln($e->getMessage());   // insert query
    }
        $output->writeln($check);

    }
}
