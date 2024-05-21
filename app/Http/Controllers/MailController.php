<?php

namespace App\Http\Controllers;

use App\Mail\MailTest;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail()
    {

        $mail = new MailTest();

        //dd($mail->content());
        $sent = Mail::to('hugojuggernot@gmail.com')->send($mail);

        //dd($sent);

        return view("mailtest");

    }
}
