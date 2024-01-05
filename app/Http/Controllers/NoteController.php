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

        // Récupérer le chemin de la note par son ID
        $note = Note::findOrFail($id);
        $path = storage_path('app/' . $note->path);

        // Lire le contenu du fichier
        $content = File::get($path);
        return view("note.NoteView",
            ['content' => $content],
            ['note' => $note]);
    }

    public function Add()
    {
        return view("note.AddNote");
    }

    // Avec AJAX
    public function saveNote(Request $request)
    {
        // Récupérer le contenu du textarea
        $content = $request->input('content');
        $user_id = $request->input("user_id");
        $note_id = $request->input("note_id");

        if($user_id == Auth::user()->id){
            $note = Note::where("note_id","=",$note_id)->first();

            if($note->owner_id == Auth::user()->id){
                $check = Storage::put($note->path,$content);
                return response()->json(['success' => true]);
            }


        }
    }


        // Logique de sauvegarde ici (par exemple, enregistrer dans la base de données ou dans un fichier)

        public function Store(Request $request)
    {
        // Nom du dossier à créer
        $name = $request->get("add-note");
        // Chemin du dossier à créer
        $path_current = $request->get("path_current");

        // Verif de la localisation
        if($path_current == "Racine")
            $path_current = "/user_" . Auth::user()->id;
        $path_current .= "/";
        $path_final = $path_current . $name;
        //dd($path_final);

        // Creation du model
        $newNote = new Note();
        $newNote->owner_id = Auth::user()->id;
        $newNote->name = $name;
        $newNote->path = $path_final;

        // Persistance + save
        Storage::put($newNote->path,"");
        $newNote->save();
        return redirect()->back()->with("success","La note a bien été créer !");
    }

        public function Delete(Request $request)
        {
            $id =  $request->get("id");
            $note = Note::find($id);
            //dd(storage_path($folder->path));
            //dd(Storage::exists($folder->path));
            Storage::delete($note->path);
            $note->delete();
            return redirect()->back()->with(["success" => "Note supprimé avec succès"]);
        }
}
