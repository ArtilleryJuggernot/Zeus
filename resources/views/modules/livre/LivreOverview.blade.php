@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des livres</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{asset("css/category.css")}}">

</head>


@if(session("success"))
    <h3>{{session("success")}}</h3>
@endif


<body>


<div class="add-projet">
    <h2>Ajouter un livre : </h2>
    <form action="{{ route('store_livre') }}" method="POST">
        @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="livre_name">Nom du livre :</label>
        <input type="text" id="livre_name" name="livre_name" required>
        <br>
        <label for="startPage">A quelle page commence le livre ?</label>
        <input type="number" min="0" value="0" id="startPage" name="startPage">
        <br>
        <label for="endPage">A quelle page fini le livre ?</label>
        <input type="number" min="0" id="endPage" name="endPage">
        <br>
        <label for="delta">En combien de temps souhaitez vous finir le livre ?</label>
        <input type="number" min="0" id="delta_num" name="dt_num">

        <select name="delta_type" id="category">
                <option value="jours">jours</option>
                <option value="semaines">semaines</option>
                <option value="mois">mois</option>
        </select>


        <label></label>




        <input type="submit" value="Commencez le livre">
    </form>
</div>

<h3>Liste des livres en cours</h3>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($userLivreUnDone as $projet)
        <div class="folder-card">
            <a href="{{route("projet_view",$projet->id)}}" ><h3>{{ $projet->name}}</h3></a>
            <form action="{{route("archive_project")}}" method="POST">
                <input name="project_id" type="hidden" value="{{$projet->id}}">
                <input  type="submit" value="Marquer comme terminé">
                @csrf
            </form>
            <form action="{{route("delete_project")}}" method="POST">
                <input name="project_id" type="hidden" value="{{$projet->id}}">
                <input type="submit" value="Supprimer le projet">
                @csrf
            </form>


            <div class="list-cat">
                @foreach($projet->categories as $category => $id)
                    @php
                        $category = \App\Models\Categorie::find($category);
                    @endphp
                    <div class="category" style="background-color: {{ $category->color }};">
                        {{ $category->category_name }}
                    </div>
                @endforeach
            </div>

            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>


<h3>Liste des projets marqué comme terminé</h3>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($userLivreDone as $projet)
        <div class="folder-card">
            <a href="{{route("projet_view",$projet->id)}}" ><h3>{{ $projet->name}}</h3></a>
            <form action="{{route("archive_project")}}" method="POST">
                <input name="project_id" type="hidden" value="{{$projet->id}}">
                <input  type="submit" value="Marque comme en cours">
                @csrf
            </form>
            <form action="{{route("delete_project")}}" method="POST">
                <input name="project_id" type="hidden" value="{{$projet->id}}">
                <input type="submit" value="Supprimer le projet">
                @csrf
            </form>
            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>
</body>
</html>

@include("includes.footer")
