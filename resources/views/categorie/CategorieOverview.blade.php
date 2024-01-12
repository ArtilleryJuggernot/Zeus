@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des categories</title>
    <link rel="stylesheet" href="/css/folder/Overview.css"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
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
        <input type="submit" value="Créer la catégorie">
    </form>
</div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($categories as $categorie)
        <div class="folder-card">
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
</body>

</html>

<script>
    function enableDate(){
        let radio_is_due = document.getElementById("is_due").checked
        let dt_input = document.getElementById("dt_input").disabled = !radio_is_due;
    }

    document.getElementById("is_due").addEventListener("click" ,() => enableDate());

</script>
