<?php

namespace App\Http\Controllers;

use App\Models\stats;
use Illuminate\Http\Request;

class GraphController extends Controller
{

    // Transforme une collection de stats en
    // array par date avec le nombre d'instance créer par ressources
    public static function StatsToGraphDataset($stats) : array
    {

        $dataset = array();
        foreach ($stats as $statArrayName => $statSubArray){
            $name = str_replace("Created", '', $statArrayName) ;
            foreach ($statSubArray as $statValue => $value){
                $dt = explode(" ",$value->created_at)[0];
                // Création de l'array selon la date
                if(!isset($dataset[$dt])) $dataset[$dt] = array();
                // Création de l'array selon la date et la collection
                if(!isset($dataset[$dt][$name])) $dataset[$dt][$name] = 1;
                else $dataset[$dt][$name] += 1;
            }
        }
        return $dataset;
    }
}
