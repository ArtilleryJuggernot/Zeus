@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des categories</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{asset("css/box.css")}}">

</head>
<body>

@if(session("success"))
    <h3>{{session("success")}}</h3>
@endif


@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Il y a eu des erreurs</h2>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="add-task">
    <h2>Ajouter une categorie : </h2>
    <form action="{{ route('store_categorie') }}" method="POST">
        @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="categorie_name">Nom de la catégorie:</label>
        <br>
        <input type="text" id="categorie_name" name="categorie_name" required>
        <br>
        <input type="color" name="color" required>
        <br>
        <input type="submit" value="Créer la catégorie">
    </form>
</div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($categories as $categorie)
        <div class="folder-card">
            <div class="box" style="background-color: {{ $categorie->color }}"></div>
            <h3>{{ $categorie->category_name}}</h3>
            <div class="delete">
                <form action="{{route("delete_categorie")}}" method="post">
                    <input name="id" type="hidden" value="{{$categorie->category_id}}"/>
                    <button type="submit">Delete</button>
                    @csrf
                </form>
            </div>

            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>



<div class="Searchbar">
    <h3>Recherche en fonction des catégories</h3>
    <form method="post" action="{{ route('searchCategory') }}">
        @csrf
        <label for="category">Sélectionnez une catégorie :</label>
        <select name="category" id="category">
            @foreach($categories as $categorie)
                <option value="{{ $categorie->category_id }}">{{ $categorie->category_name }}</option>
            @endforeach
        </select>
        <button type="submit">Rechercher</button>
    </form>
</div>

</body>

</html>
@include("includes.footer")
