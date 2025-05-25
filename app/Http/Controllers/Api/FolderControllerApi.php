<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderControllerApi extends Controller
{
    public function getContent($folder_id)
    {
        $user_id = Auth::id();
        $folder = Folder::findOrFail($folder_id);
        // Vérification d'accès (optionnel : à adapter selon ta logique de droits)
        // if ($folder->owner_id !== $user_id) {
        //     return response()->json(['error' => 'Non autorisé'], 403);
        // }

        // Sous-dossiers
        $subfolders = Folder::where('parent_id', $folder_id)->get(['id', 'name', 'path']);
        // Notes dans ce dossier
        $notes = Note::where('path', 'like', $folder->path . '/%')
            ->where('owner_id', $user_id)
            ->get(['id', 'name', 'path']);

        return response()->json([
            'folder' => [
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
            ],
            'subfolders' => $subfolders,
            'notes' => $notes,
        ]);
    }
} 