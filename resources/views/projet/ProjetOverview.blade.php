@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des projets</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
</head>

<body class="bg-gray-100">

<div id="notification" class="notification">
    <div class="progress"></div>
</div>

<div class="folders-header flex justify-between items-center mb-4">
    <button class="active bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Mes Projets</button>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Projets partagés</button>
</div>

<div class="add-projet bg-white rounded-lg p-4 shadow-md mb-4">
    <h2 class="font-bold text-xl mb-2">Ajouter un projet :</h2>
    <form action="{{ route("store_projet") }}" method="POST">
        @csrf
        <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="projet_name" class="font-bold">Nom du projet :</label>
        <br />
        <input type="text" id="projet_name" name="projet_name" required
               class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3" />
        <br />
        <input type="submit" value="Créer le projet"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
    </form>
</div>

<h3 class="font-bold text-xl mb-2">Liste des projets en cours</h3>

<div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($userProjectUnDone as $projet)
        <div class="task-card bg-white rounded-lg p-4 shadow-md">
            <a href="{{ route("projet_view", $projet->id) }}" class="text-black-500 font-bold text-xl underline">
                <h3>{{ $projet->name }}</h3>
            </a>
            <form action="{{ route("archive_project") }}" method="POST">
                <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                <input type="submit" value="Marquer comme terminé"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2" />
                @csrf
            </form>
            <form action="{{ route("delete_project") }}" method="POST">
                <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                <input type="submit" value="Supprimer le projet"
                       class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2" />
                @csrf
            </form>

            <div class="list-cat">
                @foreach ($projet->categories as $category => $id)
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

<h3 class="font-bold text-xl mb-2">Liste des projets marqués comme terminés</h3>

<div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($userProjetsDone as $projet)
        <div class="task-card bg-white rounded-lg p-4 shadow-md">
            <a href="{{ route("projet_view", $projet->id) }}" class="text-blue-500 font-bold hover:underline">
                <h3>{{ $projet->name }}</h3>
            </a>
            <form action="{{ route("archive_project") }}" method="POST">
                <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                <input type="submit" value="Marque comme en cours"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2" />
                @csrf
            </form>
            <form action="{{ route("delete_project") }}" method="POST">
                <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                <input type="submit" value="Supprimer le projet"
                       class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2" />
                @csrf
            </form>
            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
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
