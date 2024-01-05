<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */

    // Register User
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();



        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);


        $userId = $user->id;

        // Création du dossier pour l'utilisateur dans le répertoire "files"
        $directoryName = 'files/user_' . $userId;

        // Vérifiez si le dossier existe déjà avant de le créer
        if (!Storage::exists($directoryName)) {
            Storage::makeDirectory($directoryName);
        }

        else{
            Storage::deleteDirectory($directoryName);
        }
        return $user;


    }
}
