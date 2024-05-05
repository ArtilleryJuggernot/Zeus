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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Imagick;
use League\Flysystem\DirectoryListing;
use Psy\Util\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class ProfilController extends Controller
{
    public function View(Request $request, int $id)
    {
        $user_id = Auth::user()->id;
        $stats = $this->getUserStats($id);
        // Sauf si administrateur

        if(($id != $user_id && $user_id != 1) || User::find($id) == null) return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à visiter un profil autre que le votre");

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


    /**
     * @throws \ImagickException
     */
    public function UpdateProfilePicture(Request $request)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();


        // Validation des données entrantes
        $validator = Validator::make($request->all(), [
            'profilePicture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Accepte les GIF animés jusqu'à 5 Mo
        ]);

        // Si la validation échoue, renvoyer les erreurs
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user_id = (string) Auth::user()->id;


        // Traitement du fichier téléchargé
        if ($request->hasFile('profilePicture')) {


            $file = $request->file('profilePicture');
            $default_path = "/files/public/";


            $filename = $user_id  . ".png";
            if(Storage::has($default_path. $filename))
                Storage::delete($default_path . $filename);

            $file->storeAs("",$filename,"upload_pfp");


            if($file->extension() == "gif"){
            $image = new Imagick('storage/' . $filename);
            $image = $image->coalesceImages();

            foreach ($image as $frame) {
                // Redimensionner chaque image
                $frame->resizeImage(150, 150, Imagick::FILTER_CATROM, 0);
                // Appliquer d'autres manipulations si nécessaire

                // Rétablir la transparence
                $frame->setImageAlphaChannel(Imagick::ALPHACHANNEL_OPAQUE);

                // Passer à l'image suivante
                $image->nextImage();
            }

            $image = $image->deconstructImages();
            $image->writeImages('storage/' . $filename, true);

            $image->clear();
            $image->destroy();
            }

            Auth::user()->pfp_path = $default_path . $user_id . ".png";
            Auth::user()->save();
        }

        // Redirection avec un message de succès ou autre logique métier
        return redirect()->back()->with('success', 'Profile picture uploaded successfully!');
    }

}
