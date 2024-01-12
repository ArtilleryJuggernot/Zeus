<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\possede_categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    public function Overview(Request $request)
    {
        $user_id = Auth::user()->id;
        $categories = Categorie::where([
            ["owner_id",$user_id]
        ])->get();


        return view("categorie.CategorieOverview",
        [
            "categories" => $categories
        ]);
    }

    public function Delete(Request $request)
    {
        $validateData = $request->validate([
            "id" => ["required","integer"]
        ]);
        $id = $validateData["id"];
        $cat = Categorie::find($id);

        if(!$cat)
            return redirect()->route("home")->with("failure","La categorie que vous tentez de modifier n'existe pas");

        $cat->delete();
        return redirect()->back()->with(["success" => "La catégorie supprimé avec succès"]);
    }


    public function Store(Request $request)
    {
        $validateData = $request->validate([
                "categorie_name" => ["required","string","max:250"],
            ]);

        $name = $validateData["categorie_name"];
        $cat = new Categorie();
        $cat->category_name = $name;
        $cat->owner_id = Auth::user()->id;

        $cat->save();
        return redirect()->back()->with(["success" => "La catégorie à bien été créer"]);
    }


    // Ajoute une categorie à une ressource
    // TODO : Verif les input faire les if
    public function AddCategorieToRessource(Request $request)
    {

        $user_id = Auth::user()->id;
        //dd($request);
        $validateData = $request->validate([
            "category" => ["required","int"],
            "ressourceId" => ["required","int"],
            "ressourceType" => ["required","in:folder,note,task,project"],
        ]);

       $categoryId =  $validateData["category"];
       $ressourceId = $validateData["ressourceId"];
       $ressourceType = $validateData["ressourceType"];


       $newPossedeCat = new possede_categorie();
       $newPossedeCat->ressource_id = $ressourceId;
       $newPossedeCat->type_ressource = $ressourceType;
       $newPossedeCat->categorie_id = $categoryId;
       $newPossedeCat->owner_id = $user_id;

       $newPossedeCat->save();

       $CategoryName = Categorie::find($categoryId)->category_name;

       return redirect()->back()->with("success","La catégorie " . $CategoryName . " a bien été ajouter à la ressource");

    }

    // Enlève une categorie à une ressource
    public function RemoveCategorieToRessource(Request $request)
    {
        //dd($request);
        $validateData = $request->validate([
            "removeCategory" => ["required","int"],
        ]);

        $PossedeId = $validateData["removeCategory"];

        $ps = possede_categorie::find($PossedeId);

        // Fail
        if(!$ps){
            return redirect()->route("home")->with("failure","Erreur");
        }
        $ps->delete();

        return redirect()->back()->with("success","La categorie à bien été supprimé");


    }


}

