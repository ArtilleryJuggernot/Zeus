<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Categorie;
use App\Models\Folder;
use App\Models\Note;
use App\Models\possede_categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function OverView()
    {
        $user_id = Auth::user()->id;
        if($user_id == null){
            return view("home")->with("failure","La page des dossiers n'a pas pu être affiché");
        }
        $root_folder_id = Folder::where("path","=","/files/user_" . $user_id)->first()->folder_id;
        return $this->View($root_folder_id);
    }

    public function getFolderCategories($folderId) {
        $user_id = Auth::user()->id;

        $resourceCategories = possede_categorie::where('ressource_id', $folderId)
            ->where('type_ressource', 'folder')
            ->where('owner_id', $user_id)
            ->get();

        $allCategories = Categorie::all()->where('owner_id', $user_id);

        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();

        return $ownedCategories;
    }


    public function  getNotesCategories($note_id)
    {
        $user_id = Auth::user()->id;
        $resourceCategories = possede_categorie::where('ressource_id', $note_id)
            ->where('type_ressource', 'note')
            ->where('owner_id', $user_id)
            ->get();

        return $resourceCategories;
    }

    public function getFolderIdFromPath($folderPath) {
        $folderPath = "/" . $folderPath;
        $folder = Folder::where('path',"=",$folderPath)->first();
        if ($folder) {
            return $folder->folder_id;
        }
        return view("home")->with("failure","Une erreur s'est produite lors de l'obtention des dossiers");
    }

    public function getNoteIdFromPath($notePath) {
        $notePath = "/" . $notePath;
        $note = Note::where('path',"=",$notePath)->first();

        if ($note) {
            return $note->note_id;
        }
        return view("home")->with("failure","Une erreur s'est produite lors de l'obtention des notes");
    }

    public function getFolderContents($folderId) {

        $folderPath = Folder::find($folderId)->path;

        if(!$folderPath){
            return redirect()->route("home")->with("failure","Une erreur s'est produite lors de l'obtention d'un chemin de dossier");
        }

        $directories = Storage::directories($folderPath);
        $files = Storage::files($folderPath);


        $folderContents = [];
        foreach ($directories as $subFolder) {
                $subFolderId = $this->getFolderIdFromPath($subFolder);
                $categories = $this->getFolderCategories($subFolderId);
                $folderContents[] = [
                    'type' => 'folder',
                    'name' => basename($subFolder),
                    'path' => $subFolder,
                    'id' => $this->getFolderIdFromPath($subFolder),
                    'categories' => $categories,
                ];
            }


            foreach ($files as $file) {
                $categories = $this->getNotesCategories($this->getNoteIdFromPath($file));
                $folderContents[] = [
                    'type' => 'note',
                    'name' => basename($file),
                    'path' => $file,
                    'id' => $this->getNoteIdFromPath($file),
                    'categories' => $categories
                    // Autres détails de la note si nécessaire
                ];
            }
        return $folderContents;
    }


    // l'ID du dossier à vérifié
    private function checkHasPermissionView(int $id)
    {// Check Permission
        $user_id = Auth::user()->id;

        $acces = Acces::where([
            ["ressource_id",$id],
            ["type","folder"],
            ["dest_id",$user_id]
        ])->first();

        if($acces){ // Accès trouvé
            return $acces;
        }

        // Si c'est null

        // Si pas de permission trouvé, on remonte sur le dossier du dessus
        $folder = Folder::findOrFail($id);
        $path = $folder->path;
        // Recupérer le dossier parent
        $path_parent = "";
        $arr_path = explode("/",$path);
        for ($i = 0; $i < count($arr_path) - 1; $i++){
            if($i == count($arr_path) - 2 ) $path_parent .=  $arr_path[$i];
            else $path_parent .=  $arr_path[$i] . "/";
        }

        if($path_parent == "/files") // On a atteint la racine, il n'y a pas de droit
        {
            return null;
        }
        else
        {
            //dd($path_parent);
            $parent_folder = Folder::where("path",$path_parent)->first();
            return $this->checkHasPermissionView($parent_folder->folder_id);
        }
        //dd($path_parent);
    }


    public function View(int $id)
    {

        $user_id = Auth::user()->id;
        $folder = Folder::where("folder_id","=",$id)->first();

        if(!$folder){
             return redirect()->route("home")->with("failure","Le dossier demandé n'est pas disponible");
        }

        $folder_path = $folder->path;

        // Partage & Permission
        $usersPermissionsOnNote = Acces::getUsersPermissionsOnFolder($id);
        $perm_user = 0;
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces){
            if($acces->dest_id == $user_id){
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }




        // Check user authorization
        $pas_user = $folder->owner_id != Auth::user()->id;
        $accesRecursif = false;
        if($pas_user) // Pas l'utilisateur propriétaire, on regarde si l'utilisateur courant à les droits sur au moins un dossier supérieur
        {
            $accesRecursif = $this->checkHasPermissionView($id);
            //dd($accesRecursif);
//            dd(!$accesRecursif);
        }
//        if( ($pas_user && !$autorisation_partage) || !isset($accesRecursif)){
        //dd($pas_user && !$accesRecursif);
        if( ($pas_user) && !$accesRecursif){
             return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à voir cette ressource");
        }

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


        // Categories

// Assurez-vous que vous utilisez les modèles Eloquent correspondants
        $resourceCategories = possede_categorie::where('ressource_id', $id)
            ->where('type_ressource', "folder")
            ->where('owner_id', $user_id)->get();

// Obtenez toutes les catégories en utilisant le modèle Categorie
        $allCategories = Categorie::all()->where("owner_id", $user_id);

// Obtenez les catégories possédées par la ressource
        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();

// Séparez les catégories possédées et non possédées
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();
        $notOwnedCategories = $allCategories->whereNotIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();


        return view("folder.FolderView",
        ["folderContents" => $folderContents,
        "parent_content" => $parent_content,
            "folder_path" => $folder_path,
            "folder" => $folder,
         "usersPermissionList" => $usersPermissionsOnNote,
            "perm_user" => $perm_user,
            "ressourceCategories" => $resourceCategories,
            "ownedCategories" => $ownedCategories,
            "notOwnedCategories" => $notOwnedCategories
        ]);
    }

    public function Add()
    {
        $user_id = Auth::user()->id;
        $folders = Folder::where('owner_id', $user_id)->get(); // Récupère les dossiers de l'utilisateur
        return view("folder.AddFolder",
            ['folders' => $folders]);
    }

    // POST


    public  function Store(Request $request)
    {
        $validatedData = $request->validate([
            'add-dossier' => ['required', 'regex:/^(?!.*[.]{2})[A-Za-z0-9]+(\.[A-Za-z0-9]+)?$/'],
            'path_current' => 'required'
        ]);
        $name = $validatedData["add-dossier"];                  // Nom du dossier à créer
        $path_current = $validatedData["path_current"];         // Chemin du dossier à créer
        $user_id = Auth::user()->id;

        // Verif de la localisation
        if($path_current == "Racine")
            $path_current = "/user_" . $user_id;
        $path_current .= "/";
        $path_final = $path_current . $name;


        $newFolder = new Folder();                              // Creation du model
        $newFolder->owner_id = $user_id;
        $newFolder->name = $name;
        $newFolder->path = $path_final;


        $check = Storage::makeDirectory($newFolder->path);               // Persistance + save

        if(!$check){
            return redirect()->route("home")->with("failure","Une erreur s'est produite lors de la création d'un dossier");
        }
        $newFolder->save();
        return redirect()->back()->with("success","Le dossier a bien été créer !");
    }

    public function Delete(Request $request)
    {
        $validatedData = $request->validate([
           'id' => ["integer"]
        ]);
        $id =  $validatedData["id"];
        $folder = Folder::find($id);
        //dd(storage_path($folder->path));
        //dd(Storage::exists($folder->path));

        if(!$folder)
            return redirect()->route("home")->with("failure","Une erreur s'est produite lors de la recherche d'un dossier");



        // Supprimer les droits associés à une note

        Acces::where([
            ["ressource_id",$id],
            ["type","folder"],
        ])->delete();


        // Supprimer les catégories associés à la note

        possede_categorie::where([
            ["ressource_id",$id],
            ["type_ressource","folder"]
        ])->delete();

        Storage::deleteDirectory($folder->path);
        $folder->delete();
        return redirect()->back()->with(["success" => "Dossier supprimé avec succès"]);
    }
}
