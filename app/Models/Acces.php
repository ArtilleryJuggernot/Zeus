<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acces extends Model
{
    use HasFactory;

    //protected $primaryKey = ['ressource_id', 'type',"dest_id"];
    public $timestamps = false;
    public $table = "Acces";

    public static function getUsersPermissionsOnNote($noteId)
    {
        return self::where('type', 'note')
            ->where('ressource_id', $noteId)
            ->get();
    }

    // Méthode pour obtenir les droits des utilisateurs sur un dossier spécifique
    public static function getUsersPermissionsOnFolder($folderId)
    {
        return self::where('type', 'folder')
            ->where('ressource_id', $folderId)
            ->get();
    }

    // Méthode pour obtenir les droits des utilisateurs sur une tâche spécifique
    public static function getUsersPermissionsOnTask($taskId)
    {
        return self::where('type', 'task')
            ->where('ressource_id', $taskId)
            ->get();
    }

    // Méthode pour obtenir les droits des utilisateurs sur un projet spécifique
    public static function getUsersPermissionsOnProject($projectId)
    {
        return self::where('type', 'project')
            ->where('ressource_id', $projectId)
            ->get();
    }
}
