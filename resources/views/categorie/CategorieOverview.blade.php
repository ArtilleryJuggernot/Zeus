@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Liste des categories - Zeus</title>
    <link href="{{ asset("css/folder/Overview.css") }}" rel="stylesheet" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link href="{{ asset("css/box.css") }}" rel="stylesheet" />
    <link href="{{ asset("css/notification/notification.css") }}" rel="stylesheet" />
</head>
<body class="bg-gray-100">
<div id="notification" class="notification">
    <div class="progress"></div>
</div>

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

<div class="add-task  max-w-md p-4 bg-white rounded-md shadow-md">
    <h2 class="text-lg font-semibold mb-4">Ajouter une categorie :</h2>
    <form action="{{ route("store_categorie") }}" method="POST">
        @csrf
        <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="categorie_name" class="block mb-2">Nom de la catégorie:</label>
        <input type="text" id="categorie_name" name="categorie_name" required
               class="border border-gray-300 rounded-md py-2 px-3 mb-2 block w-full" />
        <input type="color" name="color" required class="mb-2" />
        <input type="submit" value="Créer la catégorie" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
    </form>
</div>

<div class="folders flex flex-wrap ">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($categories as $categorie)
        <div class="folder-card flex  p-4 m-4 bg-white rounded-md shadow-md">
            <div class="box" style="background-color: {{ $categorie->color }}"></div>
            <h3 class="ml-4  text-xl">{{ $categorie->category_name }}</h3>
            <div class="delete ml-auto">
                <form action="{{ route("delete_categorie") }}" method="post">
                    <input type="hidden" name="id" value="{{ $categorie->category_id }}" />
                    <button class="del px-5 py-2" type="submit">Delete</button>
                    @csrf
                </form>
            </div>
        </div>
    @endforeach
</div>

<div class="Searchbar max-w-md  p-4 bg-white rounded-md shadow-md mt-4">
    <h3 class="text-lg font-semibold mb-2">Recherche en fonction des catégories</h3>
    <form method="post" action="{{ route("searchCategory") }}">
        @csrf
        <label for="category" class="block mb-2">Sélectionnez une catégorie :</label>
        <select name="category" id="category" class="border border-gray-300 rounded-md py-2 px-3 mb-2 block w-full">
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->category_id }}">{{ $categorie->category_name }}</option>
            @endforeach
        </select>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Rechercher</button>
    </form>
</div>

</body>
<script src="{{ asset("js/notification.js") }}"></script>
<script>
    @if (session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif (session("failure"))
    showNotification("{{ session("success") }}", 'failure');
    @endif
</script>
</html>

@include("includes.footer")
