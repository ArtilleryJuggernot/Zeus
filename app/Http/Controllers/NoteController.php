<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    public function View(int $id)
    {
        $user_id = Auth::user()->id;
        // Récupérer le chemin de la note par son ID
        $note = Note::findOrFail($id);

        // Verification

        if(!$note){
            return redirect()->route("home")->with("failure","La note n'existe pas");
        }

        if($note->owner_id != Auth::user()->id) // TODO : Système d'autorisation accès
            return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à voir cette ressource");

        $path = storage_path('app/' . $note->path);

        // Lire le contenu du fichier
        $content = File::get($path);
        return view("note.NoteView",
            ['content' => $content],
            ['note' => $note]);
    }



    // Avec AJAX
    public function saveNote(Request $request)
    {
        // Récupérer le contenu du textarea

        $validateData = $request->validate([
           "content" => ["required","string"],
           "user_id" => ["required","integer"],
            "note_id" => ["required","integer"]
        ]);

        $content = $validateData["content"];
        $user_id = $validateData["user_id"];
        $note_id = $validateData["note_id"];

        if($user_id == Auth::user()->id){ // TODO : Système d'autorisation accès
            $note = Note::where("note_id","=",$note_id)->first();

            if($note->owner_id == Auth::user()->id){ // TODO : Système d'autorisation accès
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


            Storage::delete($note->path);
            $note->delete();
            return redirect()->back()->with(["success" => "Note supprimé avec succès"]);
        }
}
