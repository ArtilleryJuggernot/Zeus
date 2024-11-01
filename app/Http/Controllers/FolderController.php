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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function OverView()
    {
        $user_id = Auth::user()->id;
        if($user_id == null){
            return redirect()->route("home")->with("failure","La page des dossiers n'a pas pu être affiché");
        }
        $rootFolderID = Folder::where("path","=","/files/user_" . $user_id)->first()->id;
        return $this->View($rootFolderID);
    }

    public  function getFolderCategories($folderId) {
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
        $folder = Folder::where('path', "=", $folderPath)->first();

        return $folder ? $folder->id : null;
    }


    public function getNoteIdFromPath($notePath) {
        $notePath = "/" . $notePath;
        $note = Note::where('path',"=",$notePath)->first();

        if ($note) {
            return $note->id;
        }
        return redirect()->route("home")->with("failure","Une erreur s'est produite lors de l'obtention des notes");
    }

    public function getFolderContents($folderId) {
        $folderPath = Folder::find($folderId)->path;

        if (!$folderPath) {
            return redirect()->route("home")->with("failure", "Une erreur s'est produite lors de l'obtention d'un chemin de dossier");
        }

        $directories = Storage::directories($folderPath);
        $files = Storage::files($folderPath);

        $folderContents = [];
        foreach ($directories as $subFolder) {
            $subFolderId = $this->getFolderIdFromPath($subFolder);
            if ($subFolderId) {  // Ignore si l'ID est null
                $categories = $this->getFolderCategories($subFolderId);
                $folderContents[] = [
                    'type' => 'folder',
                    'name' => basename($subFolder),
                    'path' => $subFolder,
                    'id' => $subFolderId,
                    'categories' => $categories,
                ];
            }
        }

        foreach ($files as $file) {
            $noteId = $this->getNoteIdFromPath($file);
            if ($noteId) {  // Ignore si l'ID est null
                $categories = $this->getNotesCategories($noteId);
                $folderContents[] = [
                    'type' => 'note',
                    'name' => basename($file),
                    'path' => $file,
                    'id' => $noteId,
                    'categories' => $categories
                ];
            }
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
            return null;

        else
        {
            $parent_folder = Folder::where("path",$path_parent)->first();
            return $this->checkHasPermissionView($parent_folder->id);
        }
    }


    public function View(int $id)
    {

        $user_id = Auth::user()->id;
        $folder = Folder::where("id","=",$id)->first();

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

        }
//        if( ($pas_user && !$autorisation_partage) || !isset($accesRecursif)){
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
                    "id" => $folder_parent->id,
                    "path" => $folder_parent_path,
                    "name" => basename($folder_parent_path)
                ];
            }
        $folderContents = $this->getFolderContents($id);

        // Sort Alphabétique
            $folderContents = SortController::SortAlphaFolders($folderContents);



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
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->sortBy('category_name')->pluck('category_name', 'category_id')->toArray();
        $notOwnedCategories = $allCategories->whereNotIn('category_id', $ownedCategoryIds)->sortBy('category_name')->pluck('category_name', 'category_id')->toArray();




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

    public static function generateFolderTree($folderId)
    {
        $folderPath = Folder::find($folderId)->path;
        $segments = explode('/', $folderPath);

        $base_path = "/files/" . $segments[2]; // /files/user_X/


        $folder_root = Folder::where('path',$base_path)->first();
        $folderTree[] = [
            'id' => $folder_root->id,
            'name' => $folder_root->name
        ];
        $currentPath = '';
        // Parcours
        for ($i = 3; $i < count($segments); $i++){
            $base_path .= "/" . $segments[$i];
            $folder_root = Folder::where('path',$base_path)->first();
            $folderTree[] = [
                'id' => $folder_root->id,
                'name' => $folder_root->name
            ];
        }
        return $folderTree;
    }

    public  function Store(Request $request)
    {
        $validatedData = $request->validate([
            'add-dossier' => ['required', 'regex:/^(?=.*[A-Za-z0-9])[A-Za-z0-9._ \p{L}-]+$/u'],
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


        $check = Storage::makeDirectory($newFolder->path);        // Persistance + save

        if(!$check){
            LogsController::createFolder($user_id,0,$name,"FAILURE");
            return redirect()->route("home")->with("failure","Une erreur s'est produite lors de la création d'un dossier");
        }

        $newFolder->save();
        LogsController::createFolder($user_id,$newFolder->getKey(),$name,"SUCCESS");

        $folder_id = $newFolder->getKey();
        CategorieController::HeritageCategorie($folder_id,$newFolder->path,"folder");


        return redirect()->back()->with("success","Le dossier a bien été créer !");
    }

    public function Delete(Request $request)
    {
        $validatedData = $request->validate([
           'id' => ["integer"]
        ]);
        $id =  $validatedData["id"];
        $folder = Folder::find($id);
        $user_id = Auth::user()->id;

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
        LogsController::deleteFolder($user_id,$folder->id,$folder->name,"SUCCESS");
        $folder->delete();
        return redirect()->back()->with(["success" => "Dossier supprimé avec succès"]);
    }


    public function Download(Request $request)
    {

        $validatedData = $request->validate([
            'id' => ["integer"]
        ]);
        $id =  $validatedData["id"];
        $folder = Folder::find($id);
        $path = $folder->path;
        $PATH_MAIN = "app/files/user_" . Auth::user()->id;

        // Chemin absolu du dossier à compresser
        $folderPath = storage_path('app/' . $path);
        if (!Storage::exists($path) || !is_dir($folderPath)) {
            abort(404, 'Le dossier spécifié est introuvable.');
        }

        // Créer un nom de fichier temporaire pour le fichier ZIP
        $zipFilePath = tempnam(sys_get_temp_dir(), 'folder_zip');

        // Créer un fichier ZIP
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Impossible de créer le fichier ZIP.');
        }


        // Appeler la fonction récursive pour ajouter les fichiers et dossiers au ZIP
        $this->addFolderToZip($zip, $folderPath, $path);

        $zip->close();

        // Stream le fichier ZIP au client
        return response()->download($zipFilePath, $folder->name . '.zip')->deleteFileAfterSend(true);
    }

    private function addFolderToZip($zip, $folderPath, $relativePath)
    {
        // Récupérer tous les éléments du dossier
        $items = Storage::files($relativePath);
        $folders = Storage::directories($relativePath);

        // Ajouter les fichiers du dossier au ZIP
        foreach ($items as $item) {
            $relativeFilePath = $relativePath . '/' . basename($item);
            $itemPath = storage_path('app/' . $item);
            $this->addFileToZip($zip, $itemPath, $relativeFilePath);
        }

        // Appeler récursivement pour chaque sous-dossier
        foreach ($folders as $folder) {
            $this->addFolderToZip($zip, $folderPath, $folder);
        }
    }



    private function addFileToZip($zip, $filePath, $relativePath)
    {

            // Déchiffrer la note
            $content = File::get($filePath);

            if($relativePath[0] != "/")
                $relativePath = "/" . $relativePath;
            $note = Note::where('path',  $relativePath)->first();
            $ivSize = openssl_cipher_iv_length('aes-256-cbc');
            $iv = substr($content, 0, $ivSize);
            $encryptedData = substr($content, $ivSize);
            $decryptedData = openssl_decrypt($encryptedData, "aes-256-cbc", $note->note_key, 0, $iv);

            // Ajouter le contenu déchiffré au fichier ZIP
            $user_id_length = strlen(Auth::user()->id);
            $zip->addFromString(substr($relativePath,13 + $user_id_length) . '.md', $decryptedData);

            //$zip->addFile($filePath, $relativePath);

    }
}
