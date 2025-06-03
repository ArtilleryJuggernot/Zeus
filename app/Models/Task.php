<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $primaryKey = "id";
    public $table = "tasks";

    public function getPositionForProject($projectId)
    {
        return insideprojet::where('task_id', $this->id)
            ->where('projet_id', $projectId)
            ->value('pos');
    }


    public function projects()
    {
        return $this->belongsToMany(Projet::class, 'inside_projet', 'task_id', 'projet_id');
    }


    public function availableProjects()
    {
        // Obtenez tous les projets
        $allProjects = Projet::all();

        // Obtenez les projets liés à la tâche
        $linkedProjects = $this->projects->pluck('id')->toArray();

        // Obtenez les projets non liés à la tâche
        $availableProjects = $allProjects->whereNotIn('id', $linkedProjects);

        return $availableProjects;
    }



    public function categories()
{
    return $this->belongsToMany(
        \App\Models\Categorie::class,
        'possede_categorie',
        'ressource_id', // Clé étrangère sur la table pivot pour la tâche
        'categorie_id'  // Clé étrangère sur la table pivot pour la catégorie
    )
    ->where('type_ressource', 'task');
}

}
