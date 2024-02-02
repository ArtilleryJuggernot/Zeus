<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AccountManager()
    {
        $user = Auth::user();

        // Adminstrateur
        if($user->id != 1) return redirect()->route("home")->with("failure","Vous n'êtes pas administrateur");


        $users = User::all();
        return view("admin.AccountManager",[
            "users" => $users
        ]);
    }


    public function logs(Request $request)
    {
        return view("admin.logs");
    }


    public function banUser(User $user, Request $request)
    {
        if(!$user) return $this->AccountManager()->with("failure","L'utilisateur ciblé n'existe pas");

        $user->update(['is_banned' => true]);
        $user->save();
        return $this->AccountManager()->with("success","L'utilisateur à bien été bannis");
        // Ajoutez ici la redirection ou la réponse souhaitée
    }

    public function unbanUser(User $user)
    {
        $user->update(['is_banned' => false]);
        // Ajoutez ici la redirection ou la réponse souhaitée
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('nouveau_mot_de_passe')]);
        // Ajoutez ici la redirection ou la réponse souhaitée
    }
}
