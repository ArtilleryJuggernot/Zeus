<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

class PasswordReset extends Component
{
    public $userId;

    public function resetPassword()
    {
        // Retrieve user by ID
        $user = User::findOrFail($this->userId);

        // Generate a random password
        $newPassword = Str::random(10);

        // Update user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        // Send email with new password
        Mail::to($user->email)->send(new PasswordResetMail($user, $newPassword));

        // Notify the admin or whoever triggered the reset
        session()->flash('message', 'Le mot de passe a été réinitialisé et envoyé par email.');
    }

    public function render()
    {
        return view('livewire.password-reset');
    }
}
