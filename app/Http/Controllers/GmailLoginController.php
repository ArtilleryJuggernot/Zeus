<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\Console\Logger\ConsoleLogger;


class GmailLoginController extends Controller
{
    public function redirectToGmail()
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>REDIRECT</info>");
        return Socialite::driver('google')->redirect();
    }
    public function handleGmailCallback(Request $request)
    {
        //dd($request);

        //$user = Socialite::driver('google')->user();
        $user = Socialite::driver('google')->stateless()->user();
        dd($user);


        // Your authentication logic here
    }
}
