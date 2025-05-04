<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class ParametreAdmin extends Component
{
    public $allow_new_users;
    protected $listeners = ['updateLocation'];

    public function mount()
    {
        $this->allow_new_users = DB::table('site_settings')->value('allow_new_users');
    }

    public function updatingAllowNewUsers($value)
    {
       
        DB::table('site_settings')->updateOrInsert(
            ['id' => 1],
            ['allow_new_users' => !$value]
        );
        $this->allow_new_users = (bool) !$value;
        session()->flash('success', 'Paramètre mis à jour !');
    }


    

    #[On('updateLocation')]
    public function updateLocation($data)
    {
        // Exemple : $data = ['lat' => ..., 'long' => ...]
        // Ici, on suppose que la logique est d'activer les inscriptions si lat > 0 (à adapter selon besoin)
        $this->allow_new_users = $data['lat'] > 0;
        DB::table('site_settings')->updateOrInsert(
            ['id' => 1],
            ['allow_new_users' => $this->allow_new_users]
        );
        session()->flash('success', 'Paramètre mis à jour via updateLocation !');
    }

    public function render()
    {
        return view('livewire.parametre-admin');
    }
} 