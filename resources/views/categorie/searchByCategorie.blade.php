@include("includes.header")
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recherche de catégories - Zeus</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>
<body>

<h3>Résultat des recherches :</h3>


@foreach($ressources as $r)
    <div class="folder-card">
            @if($r->type_ressource == "note")
                <a href="{{route("note_view",$r->ressource_id)}}"><h3> 📝 - {{\App\Models\Note::find($r->ressource_id)->name}}</h3></a>
            @endif

            @if($r->type_ressource == "task")
                    <a href="{{route("view_task",$r->ressource_id)}}"><h3> 📚 - {{\App\Models\Task::find($r->ressource_id)->task_name}}</h3></a>
                @endif

            @if($r->type_ressource == "folder")
                    <a href="{{route("folder_view",$r->ressource_id)}}"><h3> 📁 - {{\App\Models\Folder::find($r->ressource_id)->name}}</h3></a>

                @endif

            @if($r->type_ressource == "project")
                    <a href="{{route("projet_view",$r->ressource_id)}}"><h3>[P] - {{\App\Models\Projet::find($r->ressource_id)->name}}</h3></a>
                @endif
    </div>
@endforeach


</body>
</html>

@include("includes.footer")
