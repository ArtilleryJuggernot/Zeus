<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Folder;
use App\Models\Note;
use App\Models\possede_categorie;
use App\Models\Projet;
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
        return redirect()->back()->with(["success" => "La catégorie a été supprimée avec succès"]);
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

        LogsController::createCategory($cat->owner_id,$cat->getKey(),$cat->category_name);

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


       $condition = possede_categorie::where([
           ["ressource_id",$ressourceId],
           ["type_ressource",$ressourceType],
           ["categorie_id",$categoryId]
       ])->get()->isEmpty();

        if($condition){
            $newPossedeCat = new possede_categorie();
            $newPossedeCat->ressource_id = $ressourceId;
            $newPossedeCat->type_ressource = $ressourceType;
            $newPossedeCat->categorie_id = $categoryId;
            $newPossedeCat->owner_id = $user_id;
            $newPossedeCat->save();
        }


       $CategoryName = Categorie::find($categoryId)->category_name;

       LogsController::AddCategoryToRessources($user_id,$categoryId,$CategoryName,$ressourceId,$ressourceType);

       return redirect()->back()->with("success","La catégorie " . $CategoryName . " a bien été ajoutée à la ressource");

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


    public static function HeritageCategorie($ressource_id,$path,$type)
    {
        $type == "note" ?  $tree = NoteController::generateNoteTree($ressource_id) : $tree = FolderController::generateFolderTree($ressource_id);
        $type == "note" ? $user_id = Note::find($ressource_id)->owner_id : $user_id = Folder::find($ressource_id)->owner_id;
        $max = count($tree) - 1;

            for ($i = 0; $i < $max; $i++){
                $folder_id = $tree[$i]["id"];
                // Obtenir les catégories du dossier parents
                $associate_cat = possede_categorie::where([
                    ["ressource_id",$folder_id],
                    ["type_ressource","folder"],
                    ["owner_id",$user_id],
                ])->get();

                foreach ($associate_cat as $asso_cat){
                    $cat_id = $asso_cat->categorie_id;
                    $condition  = possede_categorie::where([
                        ["ressource_id",$ressource_id],
                        ["type_ressource",$type],
                        ["categorie_id",$cat_id]
                    ])->get()->isEmpty();
                    if($condition){
                        $newPossedeCat = new possede_categorie();
                        $newPossedeCat->ressource_id = $ressource_id;
                        $newPossedeCat->type_ressource = $type;
                        $newPossedeCat->categorie_id = $cat_id;
                        $newPossedeCat->owner_id = $user_id;
                        $newPossedeCat->save();
                    }
                }

            }

    }


    public static function HeritageCategorieProjectToTask($task_id,$project_id)
    {
        // Hérité les catégories associés au projet à la tâche nouvellement créer ou associé
        $user_id = Projet::find($project_id)->owner_id;
        $cat_list  = possede_categorie::where(
            [
                ["ressource_id",$project_id],
                ["type_ressource","project"]
            ])->get();
        foreach ($cat_list as $cat){
            $cat_id = $cat->categorie_id;


            $condition = possede_categorie::where([
                ["ressource_id",$task_id],
                ["type_ressource","task"],
                ["categorie_id",$cat_id]
            ])->get()->isEmpty();

            if($condition){
                $newPossedeCat = new possede_categorie();
                $newPossedeCat->ressource_id = $task_id;
                $newPossedeCat->type_ressource = "task";
                $newPossedeCat->categorie_id = $cat_id;
                $newPossedeCat->owner_id = $user_id;
                $newPossedeCat->save();
            }
        }
    }
}

