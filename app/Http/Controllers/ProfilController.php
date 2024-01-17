<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function View(Request $request, int $id)
    {
        $user_id = Auth::user()->id;

        if($id != $user_id){
            return redirect()->route("home")->with("failure","Vous n'êtes pas autorisé à visiter un profil");
        }

        return view("profile.user_profile",
        [
            "user" => Auth::user(),
        ]);
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
