<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\CategorieController;

class NoteApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'parent_folder_id' => ['required', 'integer', 'exists:folders,id'],
        ]);

        $user_id = Auth::id();
        $parent = Folder::findOrFail($validated['parent_folder_id']);

        // Vérifier que le dossier appartient à l'utilisateur (optionnel selon ta logique)
        // if ($parent->owner_id !== $user_id) {
        //     return response()->json(['error' => 'Dossier non autorisé'], 403);
        // }

        // Générer le chemin de la note
        $path = $parent->path;
        if (substr($path, -1) !== '/') $path .= '/';
        $path .= $validated['name'];

        // Générer la clé de chiffrement
        $note_key = Str::random(32);

        // Préfixer le contenu avec le titre Markdown
        $content = "# " . $validated['name'];
        $content .= "\n" . $validated['content'];
        

        // Chiffrer le contenu
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($content, 'aes-256-cbc', $note_key, 0, $iv);
        $finalData = $iv . $encryptedData;

        // Stocker le fichier
        Storage::put($path, $finalData);

        // Créer la note en base
        $note = new Note();
        $note->owner_id = $user_id;
        $note->name = $validated['name'];
        $note->path = $path;
        $note->note_key = $note_key;
        $note->save();

        // Héritage des catégories du dossier parent
        CategorieController::HeritageCategorie($note->id, $note->path, 'note');

        return response()->json($note, 201);
    }
} 