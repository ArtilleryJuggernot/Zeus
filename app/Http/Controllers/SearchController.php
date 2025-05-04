<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Models\Projet;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user_id = Auth::user()->id;

        // Récupération des filtres
        $filter_notes = $request->get('filter_notes', true);
        $filter_folders = $request->get('filter_folders', true);
        $filter_tasks = $request->get('filter_tasks', true);
        $filter_projects = $request->get('filter_projects', true);
        $filter_task_status = $request->get('filter_task_status', 'all');

        $final = [];

        if ($filter_notes) {
            $resultNote = Note::where('name', 'LIKE', "%{$query}%")
                ->where('owner_id', $user_id)
                ->get();
            foreach ($resultNote as $note){
                $name = basename($note->path);
                $result = json_encode([
                    "id" => $note->id,
                    "type" => "note",
                    "name" => $name
                ]);
                array_push($final,$result);
            }
        }

        if ($filter_folders) {
            $resultFolder = Folder::where('name', 'LIKE', "%{$query}%")
                ->where('owner_id', $user_id)
                ->get();
            foreach ($resultFolder as $folder){
                $name = basename($folder->path);
                $result = json_encode([
                    "id" => $folder->id,
                    "type" => "folder",
                    "name" => $name
                ]);
                array_push($final,$result);
            }
        }

        if ($filter_tasks) {
            $taskQuery = Task::where("task_name", "LIKE", "%{$query}%")
                ->where('owner_id', $user_id);

            if ($filter_task_status === 'done') {
                $taskQuery->where('is_finish', true);
            } elseif ($filter_task_status === 'not_done') {
                $taskQuery->where('is_finish', false);
            }

            $resultTache = $taskQuery->get();
            foreach ($resultTache as $tache){
                $result = json_encode([
                    "id" => $tache->id,
                    "type" => "task",
                    "name" => $tache->task_name
                ]);
                array_push($final,$result);
            }
        }

        if ($filter_projects) {
            $resultProjet = Projet::where("name", "LIKE", "%{$query}%")
                ->where('owner_id', $user_id)
                ->get();
            foreach ($resultProjet as $projet){
                $result = json_encode([
                    "id" => $projet->id,
                    "type" => "project",
                    "name" => $projet->name
                ]);
                array_push($final,$result);
            }
        }

        $json = json_encode($final);
        return response()->json($json);
    }
}
