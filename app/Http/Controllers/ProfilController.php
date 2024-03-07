<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Folder;
use App\Models\Note;
use App\Models\Projet;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function View(Request $request, int $id)
    {
        $user_id = Auth::user()->id;
        $stats = $this->getUserStats($user_id);
        // Sauf si administrateur
        if($id != $user_id && $user_id != 1) return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à visiter un profil autre que le votre");

        return view("profile.user_profile",
        [
            "user" => User::find($id),
            "stats" => $stats,
        ]);
    }



    public static function getUserStats($user_id) {
        $stats = [];

        // Nombre de notes total
        $stats['total_notes'] = Note::where('owner_id', $user_id)->count();

        // Nombre de dossiers total
        $stats['total_folders'] = Folder::where('owner_id', $user_id)->count();

        // Nombre de projets total
        $stats['total_projects'] = Projet::where('owner_id', $user_id)->count();

        // Nombre de tâches réalisées (total) / Nombre de tâches total (total)
        $stats['completed_tasks_total'] = Task::where('owner_id', $user_id)->where('is_finish', 1)->count();
        $stats['total_tasks'] = Task::where('owner_id', $user_id)->count();

        // Nombre de tâches réalisées (hors projet) / Nombre de tâches total (hors projet)
        $stats['completed_tasks_no_project'] = Task::where('owner_id', $user_id)
            ->where('is_finish', 1)
            ->whereDoesntHave('projects')
            ->count();
        $stats['total_tasks_no_project'] = Task::where('owner_id', $user_id)
            ->whereDoesntHave('projects')
            ->count();

        // Nombre de tâches réalisées (projet) / Nombre de tâches total (projet)
        $stats['completed_tasks_project'] = Task::where('owner_id', $user_id)
            ->where('is_finish', 1)
            ->whereHas('projects')
            ->count();
        $stats['total_tasks_project'] = Task::where('owner_id', $user_id)
            ->whereHas('projects')
            ->count();

        // Nombre total de catégories
        $stats['total_categories'] = Categorie::where('owner_id', $user_id)->count();

        return $stats;
    }


    public function ChangePassword(Request $request)
    {

        $validateData = $request->validate([
            "oldpassword" => ["required","string"],
            "newpassword" => ["required","string"],
            "confirmation" => ["required","string"]
        ]);
        $user = Auth::user();
        // check si l'ancien mot de passe correspond


        // Erreur

        //dd($user->password);
        //dd(Hash::make($validateData["oldpassword"]));


        if(Hash::check($validateData["oldpassword"],$user->password)&&
            $validateData["newpassword"] == $validateData["confirmation"]
        ){
            $user->password = Hash::make($validateData["newpassword"]);
            $user->save();
            return redirect()->route("profile",$user->id)->with("success","Le mot de passe est bien mis à jour");
        }
        return redirect()->route("home")->with("failure","Erreur");
    }
}
