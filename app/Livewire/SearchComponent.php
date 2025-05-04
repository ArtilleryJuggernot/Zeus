<?php

namespace App\Livewire;

use App\Models\Folder;
use App\Models\Note;
use App\Models\Projet;
use App\Models\Task;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SearchComponent extends Component
{
    public $query;
    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 3) { // Recherche seulement après 3 caractères
            $this->performSearch();
        }
    }

    public function performSearch()
    {
        $user_id = Auth::user()->id;

        // Rechercher les dossiers
        $resultFolder = Folder::where('name', 'LIKE', "%{$this->query}%")
            ->where('owner_id', $user_id)
            ->get();

        // Rechercher les notes
        $resultNote = Note::where('name', 'LIKE', "%{$this->query}%")
            ->where('owner_id', $user_id)
            ->get();

        // Rechercher les tâches
        $resultTache = Task::where('task_name', 'LIKE', "%{$this->query}%")
            ->where('owner_id', $user_id)
            ->get();

        // Rechercher les projets
        $resultProjet = Projet::where('name', 'LIKE', "%{$this->query}%")
            ->where('owner_id', $user_id)
            ->get();

        // Combiner tous les résultats
        $this->results = collect()
            ->merge($resultFolder)
            ->merge($resultNote)
            ->merge($resultTache)
            ->merge($resultProjet)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => class_basename($item),
                    'name' => $item->name ?? $item->task_name
                ];
            });
    }

    public function render()
    {
        return view('livewire.search-component');
    }
}
