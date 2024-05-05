<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Categorie;
use App\Models\Folder;
use App\Models\Note;
use App\Models\Projet;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RefactoringController extends Controller
{


    // Fetch la ressource en fonction du type et de l'ID
    private function fetchRessource(string $type, int $id)
    {
        if($type == "folder") return Folder::find($id);
        if($type == "note") return Note::find($id);
        if($type == "project") return Projet::find($id);
        if ($type == "task") return Task::find($id);
        if ($type == "category") return Categorie::find($id);
    }
    public function Refactoring(Request $request)
    {

        $validateData = $request->validate([
            "id" => ["required", "int"],
            "type" => ["required", "in:folder,note,project,task,category"],
            "label" => ["required", "string","max:250"],
            "userId" => ["required", "integer"]
        ]);

        $id_ressource = $validateData["id"];
        $type = $validateData["type"];
        $newLabel = $validateData["label"];
        $user_id = $validateData["userId"];
        // Verification des droits

        $ressource = $this->fetchRessource($type, $id_ressource);


        // Verifier le pattern de la regex selon le type de données entrées
        if($ressource instanceof Note || $ressource instanceof Folder){
            if (!preg_match('/^(?=.*[A-Za-z0-9])[A-Za-z0-9._ \p{L}-]+$/u', $ressource->name))
                return response()->json(['message' => 'Regex invalide'], 400);
        }

        // Verification des droits utilisateurs

        $acces = Acces::where([
            ["ressource_id",$id_ressource],
            ["type",$type],
            ["dest_id",$user_id]
        ])->first();

        // Accès autorisé
        if($acces && $acces->perm == "F" || Auth::user()->id == $ressource->owner_id){

            if ($ressource instanceof Task) $ressource->task_name = $newLabel;
            else $ressource->name = $newLabel;

            if($ressource instanceof Folder || $ressource instanceof Note){
                $pathParts = explode('/', $ressource->path);

                $pathParts[count($pathParts) - 1] = $newLabel;

                $oldPath = $ressource->path;
                $ressource->path = implode('/', $pathParts);

                $newPath = $ressource->path;

                if (Storage::exists($newPath)) {
                    // Gérer le cas où un dossier avec le même nom existe déjà
                    return response()->json(['error' => 'Un dossier avec le même nom existe déjà.'], 400);
                }

                try {
                    Storage::move($oldPath, $newPath);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Impossible de renommer le dossier.'], 500);
                }

            }

            $ressource->save();
            return response()->json(['message' => "Renommage avec succès"], 200);
        }
        return response()->json(['message' => "Vous n'avez pas les droits"],400);
    }
}
