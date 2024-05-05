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
    public function handleGmailCallback()
    {
        $user = Socialite::driver('google')->user();
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln($user->getAvatar());
        $output->writeln($user->getEmail());
        $output->writeln($user->getId());
        $output->writeln($user->getNickname());


        // Your authentication logic here
    }
}
