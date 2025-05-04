<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class ParametreAdmin extends Component
{
    public $allow_new_users;

    public function mount()
    {
        // Récupérer la première instance uniquement
        $settings = DB::table('site_settings')->first();
        if (!$settings) {
            // Si aucun paramètre n'existe, créer la première instance
            DB::table('site_settings')->insert([
                'id' => 1,
                'allow_new_users' => false
            ]);
        }
        $this->allow_new_users = $settings->allow_new_users;
    }

    public function updatingAllowNewUsers($value)
    {



        if($this->allow_new_users){
            $this->allow_new_users = false;
        }
        else{
            $this->allow_new_users = true;
        }
       
        DB::table('site_settings')->updateOrInsert(
            ['id' => 1],
            ['allow_new_users' => $this->allow_new_users]
        );



        session()->flash('success', 'Paramètre mis à jour !');
    }


    


    public function render()
    {
        return view('livewire.parametre-admin');
    }
} 