<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use App\Mail\UserDeletionMail;

class DeleteUser extends Component
{
    public $userId;
    public $confirmName;
    public $isConfirmed = false;
    public $user;

    public function mount($userId)
    {
        $this->user = User::findOrFail($userId);
    }

    public function updatedConfirmName($value)
    {
        // Activer le bouton si le nom correspond
        $this->isConfirmed = ($value === $this->user->name);
    }

    public function deleteUser()
    {
        if (!$this->isConfirmed) {
            session()->flash('error', 'Veuillez confirmer le nom de l’utilisateur.');
            return;
        }

        // Suppression du dossier physique
        $userFolderPath = "files/user_" . $this->user->id;
        Storage::deleteDirectory($userFolderPath);

        // Suppression des enregistrements dans les tables avec user_id ou owner_id
        DB::transaction(function () {
            DB::table('notes')->where('owner_id', $this->user->id)->delete();
            DB::table('folders')->where('owner_id', $this->user->id)->delete();
            DB::table('tasks')->where('owner_id', $this->user->id)->delete();
            DB::table('task_priorities')->where('user_id', $this->user->id)->delete();
            DB::table('habitude')->where('user_id', $this->user->id)->delete();
            DB::table('Projet')->where('owner_id', $this->user->id)->delete();
            $this->user->delete();
        });

        // Envoi d'un email de confirmation de suppression
        Mail::to($this->user->email)->send(new UserDeletionMail($this->user->name));

        session()->flash('message', 'Le compte a été supprimé avec succès.');
        return redirect()->to('/users');
    }

    public function render()
    {
        return view('livewire.delete-user');
    }
}
