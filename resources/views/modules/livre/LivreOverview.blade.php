@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des livres</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
</head>

<div id="notification" class="notification">
    <div class="progress"></div>
</div>

<body class="bg-gray-100">

<div class="container mx-auto">

    <h1 class="font-bold text-2xl text-center">Section Module Livre 📚</h1>


    <div class="add-projet mb-8">
        <h2 class="text-lg font-bold mb-2">Ajouter un livre :</h2>
        <form action="{{ route("store_livre") }}" method="POST" class="flex flex-col space-y-4">
            @csrf
            <!-- Ajout du jeton CSRF pour la sécurité -->
            <label for="livre_name" class="font-bold">Nom du livre :</label>
            <input type="text" id="livre_name" name="livre_name" required
                   class="border border-gray-300 rounded-md px-3 py-2 w-1/2  focus:outline-none focus:border-blue-500" />
            <label for="startPage" class="font-bold">A quelle page commence le livre ?</label>
            <input type="number" min="0" value="0" id="startPage" name="startPage"
                   class="border border-gray-300 rounded-md px-3 py-2 w-1/2 focus:outline-none focus:border-blue-500" />
            <label for="endPage" class="font-bold">A quelle page fini le livre ?</label>
            <input type="number" min="0" id="endPage" name="endPage"
                   class="border border-gray-300 rounded-md px-3 py-2 w-1/2 focus:outline-none focus:border-blue-500" />



            <label for="delta" class="font-bold">En combien de temps souhaitez vous finir le livre ?</label>
            <div class="flex-row">
            <input type="number" min="0" id="delta_num" name="dt_num"
                   class="border border-gray-300 rounded-md px-3 py-2 w-1/3 focus:outline-none focus:border-blue-500" />

            <select name="delta_type" id="category"
                    class="border border-gray-300 rounded-md px-3 py-2 w-1/3 focus:outline-none focus:border-blue-500">
                <option value="jours">jours</option>
                <option value="semaines">semaines</option>
                <option value="mois">mois</option>
            </select>
            </div>

            <input type="submit" value="Commencez le livre"
                   class="bg-blue-500 text-white font-bold px-4 py-2 w-1/2 rounded cursor-pointer hover:bg-blue-600" />
        </form>
    </div>

    <h3 class="text-lg font-bold mb-4">Liste des livres en cours</h3>

    <div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Boucle pour afficher les dossiers -->
        @foreach ($userLivreUnDone as $projet)
            <div class="task-card bg-white p-4 rounded-md shadow-md">
                <a href="{{ route("projet_view", $projet->id) }}" class="text-blue-500 hover:underline">
                    <h3 class="font-bold">{{ $projet->name }}</h3>
                </a>
                <form action="{{ route("archive_project") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                    <input type="submit" value="Marquer comme terminé"
                           class="bg-green-500 text-white font-bold px-4 py-2  rounded cursor-pointer hover:bg-green-600" />
                    @csrf
                </form>
                <form action="{{ route("delete_project") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                    <input type="submit" value="Supprimer le projet"
                           class="bg-red-500 text-white font-bold px-4 py-2  rounded cursor-pointer hover:bg-red-600" />
                    @csrf
                </form>
                <div class="list-cat flex flex-wrap mt-4">
                    @foreach ($projet->categories as $category => $id)
                        @php
                            $category = \App\Models\Categorie::find($category);
                        @endphp
                        <div class="category bg-gray-200 rounded-full px-3 py-1 mr-2 mb-2"
                             style="background-color: {{ $category->color }};">
                            {{ $category->category_name }}
                        </div>
                    @endforeach
                </div>
                <!-- Autres détails du dossier si nécessaire -->
            </div>
        @endforeach
    </div>

    <h3 class="text-lg font-bold mb-4">Liste des projets marqués comme terminés</h3>

    <div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Boucle pour afficher les dossiers -->
        @foreach ($userLivreDone as $projet)
            <div class="task-card bg-white p-4 rounded-md shadow-md">
                <a href="{{ route("projet_view", $projet->id) }}" class="text-blue-500 hover:underline">
                    <h3 class="font-bold">{{ $projet->name }}</h3>
                </a>
                <form action="{{ route("archive_project") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                    <input type="submit" value="Marque comme en cours"
                           class="bg-blue-500 text-white font-bold px-4 py-2 w-1/2 rounded cursor-pointer hover:bg-blue-600" />
                    @csrf
                </form>
                <form action="{{ route("delete_project") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                    <input type="submit" value="Supprimer le projet"
                           class="bg-red-500 text-white font-bold px-4 py-2 w-1/2 rounded cursor-pointer hover:bg-red-600" />
                    @csrf
                </form>
                <!-- Autres détails du dossier si nécessaire -->
            </div>
        @endforeach
    </div>
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
