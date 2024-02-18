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
    public static function createCategory($user_id,$category_id,$category_name)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(SUCCESS) CREATE CATEGORY";
        $logs->ressource_id = $category_id;
        $logs->ressource_type = "category";
        $logs->content = "Ajout d'une nouvelle catégorie avec l'id n°" . $category_id
            . " et le nom : " . $category_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ").";

        $logs->save();
    }
    public static function deleteCategory($user_id,$category_id,$category_name)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(SUCCESS) DELETE CATEGORY";
        $logs->ressource_id = $category_id;
        $logs->ressource_type = "category";
        $logs->content = "Suppresion de la catégorie avec l'id n°" . $category_id
            . " et le nom : " . $category_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ").";

        $logs->save();
    }
    public static function AddCategoryToRessources($user_id,$category_id,$category_name,$ressource_id,$type_ressource)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(SUCCESS) AJOUT RESSOURCE TO CATEGORY";
        $logs->ressource_id = $category_id;
        $logs->ressource_type = "category";
        $logs->content = "Ajout de la catégorie avec l'id n°" . $category_id
            . " et le nom : " . $category_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")
            à la ressource de type " . $type_ressource . " avec l'ID : " . $ressource_id;

        $logs->save();
    }
    public static function RemoveCategoryToRessources($user_id,$category_id,$category_name,$ressource_id,$type_ressource)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(SUCCESS) REMOVE RESSOURCE TO CATEGORY";
        $logs->ressource_id = $category_id;
        $logs->ressource_type = "category";
        $logs->content = "Suppresion de la catégorie avec l'id n°" . $category_id
            . " et le nom : " . $category_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")
            pour la ressource de type " . $type_ressource . " avec l'ID : " . $ressource_id;

        $logs->save();
    }

    // Note

    public static function createNote($user_id,$note_id,$note_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") CREATION NOTE";
        $logs->ressource_id = $note_id;
        $logs->ressource_type = "note";
        $logs->content = "Création de la note avec l'id n°" . $note_id
            . " et le nom : " . $note_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")";

        $logs->save();
    }

    public static function deleteNote($user_id,$note_id,$note_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") DELETE NOTE";
        $logs->ressource_id = $note_id;
        $logs->ressource_type = "note";
        $logs->content = ($action_status == "FAILURE") ? "Aucune " : "" .  "Suppression de la note avec l'id n°" . $note_id
            . " et le nom : " . $note_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")";


        if($action_status == "FAILURE")
            $logs->content .= " (Autorisation insuffisante)";

        $logs->save();
    }

    public static function saveNote($user_id,$note_id,$note_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") SAVE NOTE";
        $logs->ressource_id = $note_id;
        $logs->ressource_type = "note";
        $logs->content =  ($action_status == "FAILURE") ? "Aucune " : "" .  "Sauvegarde de la note avec l'id n°" . $note_id
            . " et le nom : " . $note_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")";


        if($action_status == "FAILURE")
            $logs->content .= " (Autorisation insuffisante)";

        $logs->save();
    }


    // Folder


    public static function createFolder($user_id,$folder_id,$folder_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") CREATE FOLDER";
        $logs->ressource_id = $folder_id;
        $logs->ressource_type = "folder";
        $logs->content = ($action_status == "FAILURE") ? "Aucune " : "" .  "Création du dossier avec l'id n°" . $folder_id
            . " et le nom : " . $folder_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")";


        if($action_status == "FAILURE")
            $logs->content .= " (Autorisation insuffisante)";

        $logs->save();
    }

    public static function deleteFolder($user_id,$folder_id,$folder_name,$action_status)
    {
        $logs = new logs();
        $logs->user_id = $user_id;
        $logs->created_at = Carbon::now();
        $logs->action = "(" . $action_status . ") DELETE FOLDER";
        $logs->ressource_id = $folder_id;
        $logs->ressource_type = "folder";
        $logs->content = ($action_status == "FAILURE") ? "Aucune " : "" .  "Suppression du dossier avec l'id n°" . $folder_id
            . " et le nom : " . $folder_name . " par l'utilisateur " . User::find($user_id)->name . "(" . $user_id . ")";


        if($action_status == "FAILURE")
            $logs->content .= " (Autorisation insuffisante)";

        $logs->save();
    }




}
