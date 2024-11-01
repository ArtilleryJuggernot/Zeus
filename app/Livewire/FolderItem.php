<?php

namespace App\Livewire;

use Livewire\Component;

class FolderItem extends Component
{
    public $folder;
    public $isEditing = false;
    public $ownedCategories;
    public $notOwnedCategories;

    public function mount($folder, $ownedCategories, $notOwnedCategories)
    {
        $this->folder = $folder;
        $this->ownedCategories = $ownedCategories;
        $this->notOwnedCategories = $notOwnedCategories;
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function stopEditing()
    {
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.folder-item');
    }
}
