<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoPageController extends Controller
{
    public function CGU()
    {
        return view("cgu");
    }
}
