<?php

namespace App\Http\Controllers;

use App\Models\logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $dateFilter = $request->input('date_filter');
        $userID = $request->input('user_id');
        $actionFilter = $request->input('action_filter');

        // Filtre des logs en fonction des paramètres
        $query = logs::query();

        if ($dateFilter === '1') {
            $query->orderByDesc('created_at');
        }

        if ($userID) {
            $query->where('user_id', $userID);
        }

        if ($actionFilter && $actionFilter != "none") {
            $query->where('action', $actionFilter);
        }

        // Récupération des logs filtrés
        $filteredLogs = $query->get();

        //dd($filteredLogs);

        // Charger les actions disponibles pour les options du filtre d'action
        $actions = logs::select('ACTION')->distinct()->get();

        return view('admin.logs', compact('filteredLogs', 'actions'));
    }



    public function banUser(User $user, Request $request)
    {

        //dd($user);
        if(!$user) return $this->AccountManager()->with("failure","L'utilisateur ciblé n'existe pas");

        $user->is_ban = 1;
        $user->save();
        return $this->AccountManager()->with("success","L'utilisateur à bien été bannis");
        // Ajoutez ici la redirection ou la réponse souhaitée
    }

    public function unbanUser(User $user)
    {
        $user->is_ban = 0;
        $user->save();
        return $this->AccountManager()->with("success","L'utilisateur à bien été débannis");
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::password(15);
        //$user->update(['password' => Hash::make('nouveau_mot_de_passe')]);
        // Ajoutez ici la redirection ou la réponse souhaitée

    }
}
