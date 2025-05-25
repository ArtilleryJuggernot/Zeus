<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminApiController extends Controller
{
    public function resetUserPassword(Request $request)
    {
        // Vérifier que l'utilisateur authentifié est admin (à adapter selon ta logique d'admin)
        $admin = Auth::user();
        if (!$admin || !$admin->is_admin) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'new_password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès pour ' . $user->email]);
    }
} 