<?php

namespace App\Http\Controllers;

use App\Models\Acces;
use App\Models\Folder;
use App\Models\Note;
use App\Models\Projet;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ShareController extends Controller
{
    // Ajouter un utilisateur à l'application
    public function NoteStore(Request $request)
    {

        $user_id = Auth::user()->id;

        $validateData = $request->validate([
            "id_share" => ["required","integer"],
            "right" => ["required","in:RO,RW,F"],
            "note_id" => ["required","integer"]
        ]);

        $note = Note::findOrFail($validateData["note_id"]);

        if(!$note) return redirect()->route("home")->with("failure","La note que vous voulez partagez n'existe pas");
        if($note->owner_id != $user_id) return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à faire cette action");

        $user_to_share = User::findOrFail($validateData["id_share"]);
        if(!$user_to_share) return redirect()->route("home")->with("failure","L'utilisateur a qui vous souhaitez partagez cette ressource n'existe pas");


        // Verification si il n'y a pas déjà un droit accordé à la personne sur la ressource

        $previousAccess = Acces::where([
            ["ressource_id",$validateData["note_id"]],
            ["type","note"],
            ["dest_id",$validateData["id_share"]]
            ])->first();

        //dd($previousAccess);

        // Il y a dejà un droit
        if($previousAccess){
            //dd($previousAccess);
            DB::enableQueryLog();
            Acces::where("id",$previousAccess->id)->first()->delete();
            //$previousAccess->delete();
        }

        // Creation de l'accès / partage
        $acces = new Acces();
        $acces->ressource_id = $validateData["note_id"];
        $acces->type = "note";
        $acces->perm = $validateData["right"];
        $acces->dest_id = $validateData["id_share"];
        $acces->id = Acces::max('id') + 1;

        $acces->save();

        return redirect()->back()->with("success","La note à bien été partagé à " . $user_to_share->name);
    }


    public function FolderStore(Request $request)
    {
        $user_id = Auth::user()->id;

        $validateData = $request->validate([
            "id_share" => ["required","integer"],
            "right" => ["required","in:RO,RW,F"],
            "folder_id" => ["required","integer"]
        ]);

        $folder = Folder::findOrFail($validateData["folder_id"]);

        if(!$folder) return redirect()->route("home")->with("failure","La note que vous voulez partagez n'existe pas");
        if($folder->owner_id != $user_id) return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à faire cette action");

        $user_to_share = User::findOrFail($validateData["id_share"]);
        if(!$user_to_share) return redirect()->route("home")->with("failure","L'utilisateur a qui vous souhaitez partagez cette ressource n'existe pas");


        // Verification si il n'y a pas déjà un droit accordé à la personne sur la ressource

        $previousAccess = Acces::where([
            ["ressource_id",$validateData["folder_id"]],
            ["type","folder"],
            ["dest_id",$validateData["id_share"]]
        ])->first();

        //dd($previousAccess);

        // Il y a dejà un droit
        if($previousAccess){
            //dd($previousAccess);
            //DB::enableQueryLog();
            Acces::where("id",$previousAccess->id)->first()->delete();
            //$previousAccess->delete();
        }

        // Creation de l'accès / partage
        $acces = new Acces();
        $acces->ressource_id = $validateData["folder_id"];
        $acces->type = "folder";
        $acces->perm = $validateData["right"];
        $acces->dest_id = $validateData["id_share"];
        $acces->id = Acces::max('id') + 1;

        $acces->save();
        //dd("ALORS");
        return redirect()->route("folder_view",$acces->ressource_id)->with("success","La dossier à bien été partagé à " . $user_to_share->name);
    }


    public function DeletePermById(int $id) // ID de la permission
    {
        $user_id = Auth::user()->id;        // Utilisateur qui fait la requête

        $acces = Acces::findOrFail($id);

        //dd($acces);

        //dd(!$acces);
        if (!$acces){
            return redirect()->route("home")->with("failure","Les droits associés que vous tentez de modifié n'existe pas");

        }



        // Recuperation de la ressource lié à l'accès
        switch ($acces->type){
            case "note":
                $ressource = Note::findOrFail($acces->ressource_id);
                break;

            case "folder":
                $ressource = Folder::findOrFail($acces->ressource_id);
                break;

            case "task":
                $ressource = Task::findOrFail($acces->ressource_id);
                break;

            case "project":
                //dd("switch cased"); <- est atteint
                $ressource = Projet::findOrFail($acces->ressource_id);

        }



        if($ressource->owner_id != $user_id)            // La ressource n'appartient pas à l'utilisateur qui fait la demande
            return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à supprimer les droits de cette note");

        // On peut supprimé l'accès
        $acces->delete();

        return redirect()->back()->with("success","Le droit à bien été supprimmé");
    }


    public function TacheStore(Request $request)
    {
        $user_id = Auth::user()->id;

        $validateData = $request->validate([
            "id_share" => ["required","integer"],
            "right" => ["required","in:RO,RW,F"],
            "task_id" => ["required","integer"]
        ]);

        $folder = Task::findOrFail($validateData["task_id"]);

        if(!$folder) return redirect()->route("home")->with("failure","La note que vous voulez partagez n'existe pas");
        if($folder->owner_id != $user_id) return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à faire cette action");

        $user_to_share = User::findOrFail($validateData["id_share"]);
        if(!$user_to_share) return redirect()->route("home")->with("failure","L'utilisateur a qui vous souhaitez partagez cette ressource n'existe pas");


        // Verification si il n'y a pas déjà un droit accordé à la personne sur la ressource

        $previousAccess = Acces::where([
            ["ressource_id",$validateData["task_id"]],
            ["type","task"],
            ["dest_id",$validateData["id_share"]]
        ])->first();

        //dd($previousAccess);

        // Il y a dejà un droit
        if($previousAccess){
            //dd($previousAccess);
            //DB::enableQueryLog();
            Acces::where("id",$previousAccess->id)->first()->delete();
            //$previousAccess->delete();
        }

        // Creation de l'accès / partage
        $acces = new Acces();
        $acces->ressource_id = $validateData["task_id"];
        $acces->type = "task";
        $acces->perm = $validateData["right"];
        $acces->dest_id = $validateData["id_share"];
        $acces->id = Acces::max('id') + 1;

        $acces->save();
        //dd("ALORS");
        return redirect()->back()->with("success","La tâche à bien été partagé à " . $user_to_share->name);

    }

    public function ProjetStore(Request $request)
    {
        $user_id = Auth::user()->id;

        $validateData = $request->validate([
            "id_share" => ["required","integer"],
            "right" => ["required","in:RO,RW,F"],
            "projet_id" => ["required","integer"]
        ]);

        $folder = Folder::findOrFail($validateData["projet_id"]);

        if(!$folder) return redirect()->route("home")->with("failure","Le projet que vous voulez partagez n'existe pas");
        if($folder->owner_id != $user_id) return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à faire cette action");

        $user_to_share = User::findOrFail($validateData["id_share"]);
        if(!$user_to_share) return redirect()->route("home")->with("failure","L'utilisateur a qui vous souhaitez partagez cette ressource n'existe pas");


        // Verification si il n'y a pas déjà un droit accordé à la personne sur la ressource

        $previousAccess = Acces::where([
            ["ressource_id",$validateData["projet_id"]],
            ["type","project"],
            ["dest_id",$validateData["id_share"]]
        ])->first();

        //dd($previousAccess);

        // Il y a dejà un droit
        if($previousAccess){
            //dd($previousAccess);
            //DB::enableQueryLog();
            Acces::where("id",$previousAccess->id)->first()->delete();
            //$previousAccess->delete();
        }

        // Creation de l'accès / partage
        $acces = new Acces();
        $acces->ressource_id = $validateData["projet_id"];
        $acces->type = "project";
        $acces->perm = $validateData["right"];
        $acces->dest_id = $validateData["id_share"];
        $acces->id = Acces::max('id') + 1;

        $acces->save();
        //dd("ALORS");
        return redirect()->route("projet_view",$acces->ressource_id)->with("success","Le projet à bien été partagé à " . $user_to_share->name);
    }

}
