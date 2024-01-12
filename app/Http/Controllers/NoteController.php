<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Folder;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
                        'id' => $folder->folder_id,
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
                        'id' => $note->note_id
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
            return $this->checkHasPermissionView($parent_folder->folder_id);
        }
        //dd($path_parent);
    }

    public function View(int $id)       // TODO : Système d'autorisation par dossier recursif
    {                                   // TODO : En voyant le dossier parent
        $user_id = Auth::user()->id;
        $note = Note::findOrFail($id);

        if(!$note) return redirect()->route("home")->with("failure","La note n'existe pas");



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
        if($note->owner_id != $user_id) // Pas l'utilisateur propriétaire, on regarde si l'utilisateur courant à les droits sur au moins un dossier supérieur
        {
            // A partir de note_id -> folder_id
            $path = $note->path;
            $path_parent = "";
            $arr_path = explode("/",$path);
            for ($i = 0; $i < count($arr_path) - 1; $i++){
                if($i == count($arr_path) - 2 ) $path_parent .=  $arr_path[$i];
                else $path_parent .=  $arr_path[$i] . "/";
            }
            $folder_id = Folder::where("path",$path_parent)->first()->folder_id;
            $accesRecursif = $this->checkHasPermissionView($folder_id); // NE PAS DONNER L'ID DE LA NOTE MAIS CELLE DU DOSSIER
            //dd($accesRecursif);
//            dd(!$accesRecursif);
        }

        if($note->owner_id != $user_id && !$accesRecursif) // Système d'autorisation accès => 2 conditons à false
            return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à voir cette ressource");

        $path = storage_path('app/' . $note->path);

        // Lire le contenu du fichier
        $content = File::get($path);

        //dd($usersPermissionsOnNote);
        return view("note.NoteView",
            ['content' => $content,
            'note' => $note,
            "usersPermissionList" => $usersPermissionsOnNote,
            "perm_user" => $accesRecursif
            ]

        );
    }



    // Avec AJAX
    public function saveNote(Request $request)
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

        if($user_id == Auth::user()->id || $autorisation || $perm_rec){ // TODO : Système d'autorisation accès
            $note = Note::where("note_id","=",$note_id)->first();

            if($note->owner_id == Auth::user()->id || $autorisation || $perm_rec){ // TODO : Système d'autorisation accès
                $check = Storage::put($note->path,$content);
                return response()->json(['success' => true]);
            }

        }
    }


        // Logique de sauvegarde ici (par exemple, enregistrer dans la base de données ou dans un fichier)

        public function Store(Request $request)
    {

        $validateData = $request->validate([
           "add-note" => ["required",'regex:/^(?!.*[.]{2})[A-Za-z0-9]+(\.[A-Za-z0-9]+)?$/'],
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

        // Persistance + save
        Storage::put($newNote->path,"");
        $newNote->save();
        return redirect()->back()->with("success","La note a bien été créer !");
    }

        public function Delete(Request $request)
        {
            // TODO validate
            $validateData = $request->validate([
                "id" => ["required","integer"]
            ]);
            $id =  $validateData["id"];
            $note = Note::find($id);

            if(!$note)
                return redirect()->route("home")->with("failure","La note que vous souhaitez supprimé n'a pas été trouvé");


            $user_id = Auth::user()->id;

            if($note->owner_id != $user_id)
                return redirect()->route("home")->with("failure","Vous n'êtes pas autoriser à modifier sur cette ressource");

            //dd(storage_path($folder->path));
            //dd(Storage::exists($folder->path));


            // Supprimer les droits associés à une note
            Acces::where([
                ["ressource_id",$id],
                ["type","note"],
            ])->delete();

            Storage::delete($note->path);
            $note->delete();
            return redirect()->back()->with(["success" => "Note supprimé avec succès"]);
        }
}
