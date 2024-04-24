@include("includes.header")

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Liste des projets</title>
        <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
        <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
        <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
        <link
            rel="stylesheet"
            href="{{ asset("css/notification/notification.css") }}"
        />
    </head>

    <div id="notification" class="notification">
        <div class="progress"></div>
    </div>

    <body>
        <div class="folders-header">
            <button class="active">Mes Projets</button>
            <button>Projets partagés</button>
        </div>

        <div class="add-projet">
            <h2>Ajouter un projet :</h2>
            <form action="{{ route("store_projet") }}" method="POST">
                @csrf
                <!-- Ajout du jeton CSRF pour la sécurité -->
                <label for="projet_name">Nom du projet :</label>
                <br />
                <input
                    type="text"
                    id="projet_name"
                    name="projet_name"
                    required
                />
                <br />
                <input type="submit" value="Créer le projet" />
            </form>
        </div>

        <h3>Liste des projets en cours</h3>

        <div class="folders">
            <!-- Boucle pour afficher les dossiers -->
            @foreach ($userProjectUnDone as $projet)
                <div class="folder-card">
                    <a href="{{ route("projet_view", $projet->id) }}">
                        <h3>{{ $projet->name }}</h3>
                    </a>
                    <form
                        action="{{ route("archive_project") }}"
                        method="POST"
                    >
                        <input
                            name="project_id"
                            type="hidden"
                            value="{{ $projet->id }}"
                        />
                        <input type="submit" value="Marquer comme terminé" />
                        @csrf
                    </form>
                    <form action="{{ route("delete_project") }}" method="POST">
                        <input
                            name="project_id"
                            type="hidden"
                            value="{{ $projet->id }}"
                        />
                        <input type="submit" value="Supprimer le projet" />
                        @csrf
                    </form>

                    <div class="list-cat">
                        @foreach ($projet->categories as $category => $id)
                            @php
                                $category = \App\Models\Categorie::find($category);
                            @endphp

                            <div
                                class="category"
                                style="
                                    background-color: {{ $category->color }};
                                "
                            >
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
            @foreach ($userProjetsDone as $projet)
                <div class="folder-card">
                    <a href="{{ route("projet_view", $projet->id) }}">
                        <h3>{{ $projet->name }}</h3>
                    </a>
                    <form
                        action="{{ route("archive_project") }}"
                        method="POST"
                    >
                        <input
                            name="project_id"
                            type="hidden"
                            value="{{ $projet->id }}"
                        />
                        <input type="submit" value="Marque comme en cours" />
                        @csrf
                    </form>
                    <form action="{{ route("delete_project") }}" method="POST">
                        <input
                            name="project_id"
                            type="hidden"
                            value="{{ $projet->id }}"
                        />
                        <input type="submit" value="Supprimer le projet" />
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
