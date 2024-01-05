<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function HomeView()
    {
        $user = Auth::user();
        return view("home",[
            "user" => $user
        ]);
    }

    function AboutView(){
        return view("about.about");
    }
}
