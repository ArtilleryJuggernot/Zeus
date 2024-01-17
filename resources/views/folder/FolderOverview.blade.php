@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des dossiers</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}">
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>
<body>


<div class="folders-header">
    <button class="active">Mes dossiers</button>
    <button>Dossiers partagés</button>
</div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($userFolders as $folder)
        <div class="folder-card">
            @php
                $folder_name = explode("/",$folder->path);
            @endphp
            <h3>{{ end($folder_name)}}</h3>
            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>
</body>


</html>
