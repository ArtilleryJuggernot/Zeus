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
    public function AccountManager(Request $request)
    {
        $user = Auth::user();

        // Adminstrateur
        if($user->id != 1) return redirect()->route("home")->with("failure","Vous n'êtes pas administrateur");

        $sort = $request->input('sort', 'id_desc');
        $users = User::query();

        if ($sort === 'id_asc') {
            $users = $users->orderBy('id', 'asc');
        } elseif ($sort === 'id_desc') {
            $users = $users->orderBy('id', 'desc');
        } elseif ($sort === 'last_login') {
            $users = $users->orderBy('last_login_at', 'desc');
        } elseif ($sort === 'most_resources') {
            // On charge tout et on trie en PHP (sinon requête complexe)
            $users = $users->get()->sortByDesc(function($u) {
                return $u->notes()->count() + $u->folders()->count() + $u->tasks()->count() + $u->projets()->count();
            });
            return view("admin.AccountManager",["users" => $users, "sort" => $sort]);
        }
        $users = $users->get();
        return view("admin.AccountManager",["users" => $users, "sort" => $sort]);
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
        if(!$user) return $this->AccountManager($request)->with("failure","L'utilisateur ciblé n'existe pas");

        $user->is_ban = 1;
        $user->save();
        return $this->AccountManager($request)->with("success","L'utilisateur à bien été bannis");
        // Ajoutez ici la redirection ou la réponse souhaitée
    }

    public function unbanUser(User $user)
    {
        $user->is_ban = 0;
        $user->save();
        return $this->AccountManager($request)->with("success","L'utilisateur à bien été débannis");
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::password(15);
        //$user->update(['password' => Hash::make('nouveau_mot_de_passe')]);
        // Ajoutez ici la redirection ou la réponse souhaitée
    }

    public function impersonate(User $user)
    {
        Auth::login($user);
        return redirect()->route('home')->with('success', "Vous êtes maintenant connecté en tant que {$user->name}");
    }

    public function deleteUser(User $user)
    {
        if($user->id == 1) return back()->with('failure', "Impossible de supprimer l'administrateur.");
        $user->delete();
        return back()->with('success', "L'utilisateur a bien été supprimé.");
    }
}
