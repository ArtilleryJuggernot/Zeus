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

    public function updateNote(Request $request, $id)
    {
        $user_id = Auth::id();
        $note = \App\Models\Note::findOrFail($id);
        if ($note->owner_id !== $user_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'mode' => ['nullable', 'in:replace,append'],
        ]);

        // Modification du nom
        if (isset($validated['name'])) {
            $note->name = $validated['name'];
        }

        // Modification du contenu
        if (isset($validated['content'])) {
            $path = $note->path;
            $note_key = $note->note_key;
            $mode = $validated['mode'] ?? 'replace';

            // Récupérer l'ancien contenu déchiffré
            $old_content = null;
            if (\Illuminate\Support\Facades\Storage::exists($path)) {
                $file_content = \Illuminate\Support\Facades\Storage::get($path);
                $ivSize = openssl_cipher_iv_length('aes-256-cbc');
                $iv = substr($file_content, 0, $ivSize);
                $encryptedData = substr($file_content, $ivSize);
                $old_content = openssl_decrypt($encryptedData, 'aes-256-cbc', $note_key, 0, $iv);
            }

            if ($mode === 'append' && $old_content !== null) {
                $new_content = $old_content . "\n" . $validated['content'];
            } else {
                // replace ou pas d'ancien contenu
                $new_content = $validated['content'];
            }

            // Préfixer le contenu avec le titre Markdown
            $final_content = "# " . ($note->name ?? '') . "\n" . $new_content;

            // Chiffrer et sauvegarder
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
            $encryptedData = openssl_encrypt($final_content, 'aes-256-cbc', $note_key, 0, $iv);
            $finalData = $iv . $encryptedData;
            \Illuminate\Support\Facades\Storage::put($path, $finalData);
        }

        $note->save();
        return response()->json(['message' => 'Note modifiée avec succès', 'note' => $note]);
    }

    public function getNotesByFolder($folder_id)
    {
        $user_id = Auth::id();
        $notes = \App\Models\Note::where('owner_id', $user_id)
            ->where('path', 'like', "%/user_{$user_id}/%")
            ->where('path', 'like', "%/{$folder_id}/%")
            ->get();
        // Alternative plus simple si le path contient le nom du dossier parent
        // $notes = \App\Models\Note::where('owner_id', $user_id)->where('parent_folder_id', $folder_id)->get();
        return response()->json($notes);
    }
} 