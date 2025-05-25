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
        $basePath = $folder->path;
        $baseDepth = substr_count($basePath, '/');

        // Sous-dossiers directs
        $subfolders = Folder::where('path', 'like', $basePath . '/%')
            ->get(['id', 'name', 'path'])
            ->filter(function ($subfolder) use ($baseDepth) {
                // On ne garde que les enfants directs (profondeur +1)
                return substr_count($subfolder->path, '/') === $baseDepth + 1;
            })
            ->values();

        // Notes enfants directs
        $notes = Note::where('path', 'like', $basePath . '/%')
            ->where('owner_id', $user_id)
            ->get(['id', 'name', 'path'])
            ->filter(function ($note) use ($baseDepth) {
                // On ne garde que les notes enfants directs (profondeur +1)
                return substr_count($note->path, '/') === $baseDepth + 1;
            })
            ->values();

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