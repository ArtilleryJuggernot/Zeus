@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des dossiers</title>
    <link rel="stylesheet" href="/css/folder/Overview.css"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>
<body>

@if(session("success"))
    {{session("success")}}
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



<div class="previous-folder">

    @if($parent_content["id"] == "Racine")
        <h3>Vous êtes à la racine</h3>
    @else
        <h3>Dossier parent : <a href="{{route("folder_view",$parent_content["id"])}}"><h3>{{$parent_content["name"]}}</h3></a></h3>
        <h3>Vous êtes dans le dossier : {{$folder_name}}</h3>
    @endif
</div>

<div>

</div>

<div class="add-section">
    <div class="add-dossier">
        <form action="{{route("add_folder")}}" method="post">
        <label for="add-dossier">Entrez le nom du dossier que vous souhaitez ajouter :</label>
            <input name="add-dossier" type="text"/>
            <input type="hidden" name="path_current" value="{{$folder_path}}">
            <input type="submit" value="Envoyer" />

            @csrf
        </form>
    </div>

    <div class="add-note">
        <form action="{{route("add_note")}}" method="post">
            <label for="add-note">Entrez la note que vous souhaitez ajouter :</label>
            <input name="add-note" type="text"/>
            <input type="hidden" name="path_current" value="{{$folder_path}}">
            <input type="submit" value="Envoyer" />
            @csrf
        </form>
    </div>

</div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($folderContents as $item)
        <div class="folder-card">
           @if($item["type"] == "folder")
               <a href="{{route("folder_view",$item["id"])}}"><h3>[D] - {{$item["name"]}}</h3></a>
               <div class="delete">
                   <form action="{{route("delete_folder")}}" method="post">
                       <input name="id" type="hidden" value="{{$item["id"]}}"/>
                       <button type="submit">Delete</button>
                       @csrf
                   </form>
               </div>
            @else
                <a href="{{route("note_view",$item["id"])}}"><h3>[N] - {{$item["name"]}}</h3></a>
                <div class="delete">
                    <form action="{{route("delete_note")}}" method="post">
                    <input name="id" type="hidden" value="{{$item["id"]}}"/>
                    <button type="submit">Delete</button>
                        @csrf
                    </form>
                </div>
            @endif

        </div>
    @endforeach
</div>
</body>



</html>
