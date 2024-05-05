<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Middleware\Authenticate;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Logger\ConsoleLogger;


class GmailLoginController extends Controller
{
    public function redirectToGmail()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGmailCallback(Request $request)
    {

        $userProvider = Socialite::driver('google')->stateless()->user();

        // check si un utilisateur à déjà cette adresse mail
        $userAlready = User::where(["email" => $userProvider->email])->first();

        if ($userAlready != null){
            auth()->login($userAlready,false);
	        return redirect()->route('home');
        }


        $password = Str::password();

        $userTolog = User::create([
            'name' => $userProvider->name,
            'email' => $userProvider->email,
            'password' => Hash::make($password),

        ]);




        $userId = $userTolog->id;

        // Création du dossier pour l'utilisateur dans le répertoire "files"
        $directoryName = 'files/user_' . $userId;
        $folder = new Folder();
        $folder->owner_id = $userId;
        $folder->path = '/' . $directoryName;
        $folder->name = "user_" . $userId;
        $folder->save();
        // Vérifiez si le dossier existe déjà avant de le créer
        if (!Storage::exists($directoryName)) {
            Storage::makeDirectory($directoryName);
        }

        else{
            Storage::deleteDirectory($directoryName);
            Storage::makeDirectory($directoryName);
        }

        // Store avatar


        $curlCh = curl_init();
        curl_setopt($curlCh, CURLOPT_URL, $userProvider->avatar);
        curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlCh, CURLOPT_SSLVERSION,3);
        $curlData = curl_exec ($curlCh);
        curl_close ($curlCh);
        if(!empty($curlData)){
            Storage::disk('local')->put('app/public/' . $userTolog->id . '.png', $curlData);

        }

	Auth::loginUsingId($userId);
	session()->regenerate();
        return redirect("/home");

        // Your authentication logic here
    }
}
