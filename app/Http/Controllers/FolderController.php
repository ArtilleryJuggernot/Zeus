<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function OverView()
    {
        $user_id = Auth::user()->id; // Récupérer l'utilisateur actuel
        $root_folder_id = Folder::where("path","=","/files/user_" . $user_id)->first()->folder_id;
        return $this->View($root_folder_id);
    }


    public function getFolderIdFromPath($folderPath) {
        $folderPath = "/" . $folderPath;
        $folder = Folder::where('path',"=",$folderPath)->first();
        if ($folder) {
            return $folder->folder_id;
        }
        return null;
    }

    public function getNoteIdFromPath($notePath) {
        $notePath = "/" . $notePath;
        $note = Note::where('path',"=",$notePath)->first();

        if ($note) {
            return $note->note_id;
        }
        return null;
    }

    public function getFolderContents($folderId) {

        $folderPath = Folder::find($folderId)->path;
            $directories = Storage::directories($folderPath);
            $files = Storage::files($folderPath);


        $folderContents = [];
        foreach ($directories as $subFolder) {
                $folderContents[] = [
                    'type' => 'folder',
                    'name' => basename($subFolder),
                    'path' => $subFolder,
                    'id' => $this->getFolderIdFromPath($subFolder)
                ];
            }


            foreach ($files as $file) {
                $folderContents[] = [
                    'type' => 'note',
                    'name' => basename($file),
                    'path' => $file,
                    'id' => $this->getNoteIdFromPath($file)
                    // Autres détails de la note si nécessaire
                ];
            }
        return $folderContents;
    }



    public function View(int $id)
    {
        $folder_path = Folder::where("folder_id","=",$id)->first()->path;

        $folder_parent_path = dirname($folder_path);

        $folder_parent = Folder::where("path","=",$folder_parent_path)->first();
            // On est déjà à la racine
            if($folder_parent == null){
                $parent_content = [
                    "id" => "Racine",
                ];
            }
            else{
                $parent_content = [
                    "id" => $folder_parent->folder_id,
                    "path" => $folder_parent_path,
                    "name" => basename($folder_parent_path)
                ];
            }

        $folderContents = $this->getFolderContents($id);
        return view("folder.FolderView",
        ["folderContents" => $folderContents,
        "parent_content" => $parent_content,
            "folder_path" => $folder_path]);
    }

    public function Add()
    {
        $user = Auth::user(); // Obtenez l'utilisateur authentifié
        $folders = Folder::where('owner_id', $user->id)->get(); // Récupère les dossiers de l'utilisateur
        return view("folder.AddFolder",
            ['folders' => $folders]);
    }

    // POST
    public function Store2(Request $request)
    {
        $user = Auth::user(); // Récupérer l'utilisateur actuel

        // Validation des données du formulaire
        $validatedData = $request->validate([
            'folder_name' => 'required|max:100',
            'location' => 'required|in:root,sub_folder', // S'assurer que la valeur est 'root' ou 'sub_folder'
            'parent_folder_id' => 'nullable|exists:folders,folder_id' // Valider que l'ID du parent existe
        ]);

        // Création d'une nouvelle instance de Folder avec les données du formulaire
        $newFolder = new Folder();
        $newFolder->owner_id = $user->id;
        //$newFolder->folder_name = $validatedData['folder_name'];

        // Si l'utilisateur choisit d'ajouter à la racine
        if ($validatedData['location'] === 'root') {
            //$newFolder->parent_folder_id = null; // Laisser parent_folder_id comme NULL pour la racine
            $newFolder->path = '/files/user_' . Auth::user()->id . "/" . $validatedData['folder_name'];

        } else {
            $path_parent = Folder::where("folder_id" , "=" , $validatedData["parent_folder_id"])->first()->path;
            $newFolder->path = $path_parent . "/" . $validatedData["folder_name"];
        }

        // Enregistrement du nouveau dossier dans la base de données
        Storage::makeDirectory($newFolder->path);
        $newFolder->save();

        // Redirection vers une autre page après la création du dossier (à ajuster selon vos besoins)
        return redirect()->route('folder_overview')->with('success', 'Le dossier a été créer !');
    }


    public  function Store(Request $request)
    {
        // Nom du dossier à créer
        $name = $request->get("add-dossier");
        // Chemin du dossier à créer
        $path_current = $request->get("path_current");

        // Verif de la localisation
        if($path_current == "Racine")
            $path_current = "/user_" . Auth::user()->id;
        $path_current .= "/";
        $path_final = $path_current . $name;
        //dd($path_final);

        // Creation du model
        $newFolder = new Folder();
        $newFolder->owner_id = Auth::user()->id;
        $newFolder->name = $name;
        $newFolder->path = $path_final;

        // Persistance + save
        Storage::makeDirectory($newFolder->path);
        $newFolder->save();
        return redirect()->back()->with("success","Le dossier a bien été créer !");
    }

    public function Delete(Request $request)
    {
        $id =  $request->get("id");
        $folder = Folder::find($id);
        //dd(storage_path($folder->path));
        //dd(Storage::exists($folder->path));
        Storage::deleteDirectory($folder->path);
        $folder->delete();
        return redirect()->back()->with(["success" => "Dossier supprimé avec succès"]);
    }
}
