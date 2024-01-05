<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Models\Projet;
use App\Models\Task;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // A partir de la query
    // On veut récupérer tout les dossiers, notes et taches
    // Et on renvoie le tout sous format Json
    // En 1er les dossiers
    // Puis les taches (pas encore imple TODO)
    // Puis les notes
    public function doSearch(Request $request)
    {
        $query = $request->get("query");

        $resultFolder = Folder::where('name', 'LIKE', "%{$query}%")->get();
        $resultNote = Note::where('name', 'LIKE', "%{$query}%")->get();
        // Tache et Projet
        $resultTache = Task::where("task_name","LIKE","%{$query}%")->get();
        $resultProjet = Projet::where("name","LIKE","%{$query}%")->get();
        $final = [];

        foreach ($resultNote as $note){
            $name = basename($note->path);
            $result = json_encode([
                "id" => $note->note_id,
                "type" => "note",
                "name" => $name
                ]);
            array_push($final,$result);
        }

        foreach ($resultFolder as $folder){
            $name = basename($folder->path);
            $result = json_encode([
                "id" => $folder->folder_id,
                "type" => "folder",
                "name" => $name
            ]);
            array_push($final,$result);
        }

        foreach ($resultTache as $tache){
            $result = json_encode([
               "id" => $tache->task_id,
               "type" => "task",
               "name" => $tache->task_name
            ]);
            array_push($final,$result);
        }

        foreach ($resultProjet as $projet){
            $result = json_encode([
               "id" => $projet->id,
               "type" => "project",
               "name" => $projet->name
            ]);
            array_push($final,$result);
        }


        $json = json_encode($final);
        return response()->json($json);
    }
}
