<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SortController extends Controller
{
    public static function SortAlphaFolders ($folderContents)
    {
        usort($folderContents, function($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        return $folderContents;
    }

}
