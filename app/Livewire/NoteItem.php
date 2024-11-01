<?php

namespace App\Livewire;
use Livewire\Component;

class NoteItem extends Component
{
    public $note;
    public $isEditing;
    public $ownedCategories;
    public $notOwnedCategories;

    public function mount($note, $ownedCategories, $notOwnedCategories)
    {
        $this->note = $note;
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
        return view('livewire.note-item');
    }
}
