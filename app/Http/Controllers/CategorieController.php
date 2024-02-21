<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\possede_categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


    // TODO : verif des droits
    public function Delete(Request $request)
    {
        $validateData = $request->validate([
            "id" => ["required","integer"]
        ]);
        $id = $validateData["id"];
        $cat = Categorie::find($id);

        if(!$cat)
            return redirect()->route("home")->with("failure","La categorie que vous tentez de modifier n'existe pas");




        $ps = possede_categorie::where("categorie_id",$cat->category_id)->get();
        foreach ($ps as $elem)
            $elem->delete();



        LogsController::deleteCategory(Auth::user()->id,$cat->category_id,$cat->category_name);
        $cat->delete();
        return redirect()->back()->with(["success" => "La catégorie supprimé avec succès"]);
    }


    public function Store(Request $request)
    {
        $validateData = $request->validate([
                "categorie_name" => ["required","string","max:250"],
                "color" => ["required","string"]
            ]);

        $name = $validateData["categorie_name"];
        $color = $validateData["color"];

        $cat = new Categorie();
        $cat->category_name = $name;
        $cat->color = $color;
        $cat->owner_id = Auth::user()->id;

        $cat->save();

        LogsController::createCategory($cat->owner_id,$cat->category_id,$cat->category_name);

        return redirect()->back()->with(["success" => "La catégorie à bien été crée"]);
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

       LogsController::AddCategoryToRessources($user_id,$categoryId,$CategoryName,$ressourceId,$ressourceType);

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


        $Category = Categorie::find($ps->categorie_id);
        $CategoryName = $Category->category_name;

        LogsController::RemoveCategoryToRessources(Auth::user()->id,$ps->categorie_id,$CategoryName,$ps->ressource_id,$ps->ressource_type);

        $ps->delete();

        return redirect()->back()->with("success","La categorie à bien été supprimé");


    }



    public function Search(Request $request)
    {
        //dd($request);
        $user_id = Auth::user()->id;
        $validateData = $request->validate([
            "category" => ["required","integer"]
        ]);

        $ressources = possede_categorie::where(
            [
                ["categorie_id",$validateData["category"]],
                ["owner_id",$user_id]

            ])->get();


        return view("categorie.searchByCategorie",

        [
            "ressources" => $ressources
        ]
        );

    }

}

