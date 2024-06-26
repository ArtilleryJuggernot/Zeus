<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Categorie;
use App\Models\Folder;
use App\Models\Note;
use App\Models\possede_categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class NoteController extends Controller
{

    public function listFilesAndDirectoriesRecursively($directory, $currentPath = '') {
        $result = [];

        $files = glob(rtrim($directory, '/') . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                // Récupérer le chemin actuel pour le dossier
                $subDirectoryPath = $currentPath . '/' . basename($file);

                // Récupérer l'ID depuis le chemin du dossier

                $pos = strpos($file, "/files/");
                if ($pos !== false) {
                    $path = substr($file, $pos);
                }
                //sdd($path);
                $folder = Folder::where("path", "=", $path)->first();

                if ($folder) {
                    // Ajouter l'ID à la liste des dossiers
                    $result[basename($file)] = [
                        'id' => $folder->id,
                        'content' => $this->listFilesAndDirectoriesRecursively($file, $subDirectoryPath)
                    ];
                } else {
                    // S'il n'y a pas d'ID pour le dossier, récupérer son contenu récursivement
                    $result[basename($file)] = [
                        'content' => $this->listFilesAndDirectoriesRecursively($file, $subDirectoryPath)
                    ];
                }
            } else {
                // Récupérer l'ID depuis le chemin du fichier (note)
                $pos = strpos($file, "/files/");

                if ($pos !== false) {
                    $path = substr($file, $pos);
                }

                $note = Note::where("path", "=", $path)->first();

                if ($note) {
                    // Ajouter l'ID à la liste des fichiers
                    $result[] = [
                        'file' => basename($file),
                        'id' => $note->id
                    ];
                } else {
                    $result[] = basename($file);
                }
            }
        }

        return $result;
    }

    public function OverView()
    {
        $userDirectory = storage_path('app/files/user_' . Auth::user()->id);

        $directoryContent = $this->listFilesAndDirectoriesRecursively($userDirectory);

        //dd($directoryContent);

            return view('note.NoteOverview',
            ["directoryContent" => $directoryContent]);

    }

    private function checkHasPermissionView(int $id)
    {
        // Check Permission
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

        //dd($path_parent);

        if($path_parent == "/files")  return null; // On a atteint la racine, il n'y a pas de droit

        else
        {
            $parent_folder = Folder::where("path",$path_parent)->first();
            return $this->checkHasPermissionView($parent_folder->id);
        }
        //dd($path_parent);
    }


    public static function generateNoteTree($noteId)
    {
        $notePath = Note::find($noteId)->path;
        $segments = explode('/', $notePath);

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
            if($i == count($segments) -1 )
                $folder_root = Note::where('path',$base_path)->first();
            else
            $folder_root = Folder::where('path',$base_path)->first();

            $folderTree[] = [
                'id' => $folder_root->id,
                'name' => $folder_root->name
            ];
        }
        return $folderTree;
    }


    public function View(int $id)       // TODO : Système d'autorisation par dossier recursif
    {                                   // TODO : En voyant le dossier parent
        $user_id = Auth::user()->id;
        $note = Note::findOrFail($id);

        if(!$note) return redirect()->route("home")->with("failure","La note n'existe pas");



        // Verification de la permission
        $usersPermissionsOnNote = Acces::getUsersPermissionsOnNote($id);
        $perm_user = 0;
        $autorisation_partage = false;
        foreach ($usersPermissionsOnNote as $acces){
            if($acces->dest_id == $user_id){
                $autorisation_partage = true;
                $perm_user = $acces;
                break;
            }
        }

        $accesRecursif = false;
        if (!$autorisation_partage){
            if($note->owner_id != $user_id) // Pas l'utilisateur propriétaire, on regarde si l'utilisateur courant à les droits sur au moins un dossier supérieur
            {
                // A partir de note_id -> id
                $path = $note->path;
                $path_parent = "";
                $arr_path = explode("/",$path);
                for ($i = 0; $i < count($arr_path) - 1; $i++){
                    if($i == count($arr_path) - 2 ) $path_parent .=  $arr_path[$i];
                    else $path_parent .=  $arr_path[$i] . "/";
                }
                $folderID = Folder::where("path",$path_parent)->first()->id;
                $accesRecursif = $this->checkHasPermissionView($folderID); // NE PAS DONNER L'ID DE LA NOTE MAIS CELLE DU DOSSIER
                //dd($accesRecursif);
//            dd(!$accesRecursif);
            }
        }



        if($note->owner_id != $user_id && !$autorisation_partage && !$accesRecursif) // Système d'autorisation accès => 2 conditons à false
            return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à voir cette ressource");

        $path = storage_path('app/' . $note->path);

        // Lire le contenu du fichier
        $content = File::get($path);

// Extraire l'IV des premiers octets
        $ivSize = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($content, 0, $ivSize);
        $encryptedData = substr($content, $ivSize);

// Déchiffrement
        $decryptedData = openssl_decrypt($encryptedData, "aes-256-cbc", $note->note_key, 0, $iv);

        $content = $decryptedData;


        $resourceCategories = possede_categorie::where('ressource_id', $id)
            ->where('type_ressource', "note")
            ->where('owner_id', $user_id)->get();


// Obtenez toutes les catégories en utilisant le modèle Categorie
        $allCategories = Categorie::all()->where("owner_id", $user_id);


// Obtenez les catégories possédées par la ressource
        $ownedCategoryIds = $resourceCategories->pluck('categorie_id')->toArray();

// Séparez les catégories possédées et non possédées
        $ownedCategories = $allCategories->whereIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();
        $notOwnedCategories = $allCategories->whereNotIn('category_id', $ownedCategoryIds)->pluck('category_name', 'category_id')->toArray();



        return view("note.NoteView",
            ['content' => $content,
            'note' => $note,
            "usersPermissionList" => $usersPermissionsOnNote,
            "perm_user" => $perm_user,
                "ressourceCategories" => $resourceCategories,
                "ownedCategories" => $ownedCategories,
                "notOwnedCategories" => $notOwnedCategories
            ]

        );
    }

    // Avec AJAX
    public function saveNote(Request $request): \Illuminate\Http\JsonResponse
    {
        // Récupérer le contenu du textarea


        $validateData = $request->validate([
           "content" => ["required","string"],
           "user_id" => ["required","integer"],
            "note_id" => ["required","integer"],
            "perm" => ["required","in:RO,RW,F"]
        ]);




        $content = $validateData["content"];
        $user_id = $validateData["user_id"];
        $note_id = $validateData["note_id"];
        $perm = $validateData["perm"];

        $access = Acces::where([
            ["ressource_id",$note_id],
            ["type","note"],
            ["dest_id",$user_id]
        ])->first();

        $autorisation = false;

        if($access && ($access->perm == "RW" || $access->perm == "F")) { // Verifié si il existe un accès pour l'utilisateur + auto
            $autorisation = true;
        }

        // Permission par arbo recurif dossier

        $perm_rec = $perm == "RW" || $perm == "F";



        if($user_id == Auth::user()->id || $autorisation || $perm_rec){
            $note = Note::find($note_id);

            if($note->owner_id == Auth::user()->id || $autorisation || $perm_rec){

                // Chiffrement des données



                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')); // Générer un IV aléatoire
                $encryptedData = openssl_encrypt($content, "aes-256-cbc", $note->note_key, 0, $iv);
                $finalDataEncryptedAES = $iv . $encryptedData; // Concaténer l'IV avec les données chiffrées




                $check = Storage::put($note->path,$finalDataEncryptedAES);
                LogsController::saveNote($user_id,$note_id,$note->name,"SUCCESS");
                return response()->json(['success' => true]);
            }
            LogsController::saveNote($user_id,$note_id,$note->name,"FAILURE");
            return response()->json(['failure' => false]);

        }
        return response()->json(['failure' => false]);
    }


        // Logique de sauvegarde ici (par exemple, enregistrer dans la base de données ou dans un fichier)

    public function Store(Request $request)
    {

        $validateData = $request->validate([
           "add-note" => ["required",'regex:/^(?=.*[A-Za-z0-9])[A-Za-z0-9._ \p{L}-]+$/u'],
           "path_current" => ["required","string"] // TODO : Sensible , regex chemin ?
        ]);
        // Nom du dossier à créer
        $name = $validateData["add-note"];
        // Chemin du dossier à créer
        $path_current = $validateData["path_current"];


        $user_id = Auth::user()->id;

        // Verif de la localisation
        if($path_current == "Racine")
            $path_current = "/user_" . $user_id;
        $path_current .= "/";
        $path_final = $path_current . $name;
        //dd($path_final);

        // Creation du model
        $newNote = new Note();
        $newNote->owner_id = $user_id;
        $newNote->name = $name;
        $newNote->path = $path_final;
        $newNote->note_key = Str::random(32);



        // Persistance + save


        $content = "# " . $name;
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')); // Générer un IV aléatoire
        $encryptedData = openssl_encrypt($content, "aes-256-cbc", $newNote->note_key, 0, $iv);
        $finalDataEncryptedAES = $iv . $encryptedData; // Concaténer l'IV avec les données chiffrées

        Storage::put($newNote->path, $finalDataEncryptedAES);

        $newNote->save();
        LogsController::createNote($user_id,$newNote->getKey(),$name,"SUCCESS");
        $note_id = $newNote->getKey();
        CategorieController::HeritageCategorie($note_id,$newNote->path,"note");

        return redirect()->back()->with("success","La note a bien été créée !");
    }

    public function Delete(Request $request)
        {
            // TODO verification des accès
            $validateData = $request->validate([
                "id" => ["required","integer"]
            ]);
            $id =  $validateData["id"];
            $note = Note::find($id);
            $user_id = Auth::user()->id;

            if(!$note) {
                LogsController::deleteNote($user_id,$id,"","FAILURE");
                return redirect()->route("home")->with("failure","La note que vous souhaitez supprimer n'a pas été trouvée");
            }



            if($note->owner_id != $user_id){
                LogsController::deleteNote($user_id,$id,$note->name,"FAILURE");
                return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à modifier cette ressource");
            }

            //dd(storage_path($folder->path));
            //dd(Storage::exists($folder->path));


            // Supprimer les droits associés à une note
            Acces::where([
                ["ressource_id",$id],
                ["type","note"],
            ])->delete();


            // Supprimer les catégories associés à la note

            possede_categorie::where([
                ["ressource_id",$id],
                ["type_ressource","note"]
            ])->delete();




            Storage::delete($note->path);
            $note->delete();
            LogsController::deleteNote($user_id,$id,$note->name,"SUCCESS");
            return redirect()->back()->with(["success" => "Note supprimé avec succès"]);
        }



        public function Download(Request $request)
        {
            // Valider les données de la requête
            $request->validate([
                'id' => ['required', 'integer'],
            ]);

            $id = $request->id;
            $note = Note::find($id);

            // Vérifier si la note existe
            if (!$note) {
                return redirect()->route('home')->with('failure', 'La note que vous souhaitez télécharger n\'a pas été trouvée');
            }

            // Vérifier si l'utilisateur est autorisé à accéder à cette note
            if ($note->owner_id != Auth::user()->id) {
                return redirect()->route('home')->with('failure', 'Vous n\'êtes pas autorisé à télécharger cette note');
            }

            // Déchiffrer la note
            $content = File::get(storage_path('app/' . $note->path));
            $ivSize = openssl_cipher_iv_length('aes-256-cbc');
            $iv = substr($content, 0, $ivSize);
            $encryptedData = substr($content, $ivSize);
            $decryptedData = openssl_decrypt($encryptedData, "aes-256-cbc", $note->note_key, 0, $iv);

            // Retourner la note déchiffrée en tant que téléchargement
            return response()->streamDownload(function () use ($decryptedData) {
                echo $decryptedData;
            }, $note->name . '.md');
        }
}
