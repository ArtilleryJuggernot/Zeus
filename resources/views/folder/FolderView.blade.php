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
        <h3>Vous êtes dans le dossier : {{$folder->name}}</h3>
    @endif
</div>

<div>

</div>

<button class="accordion">Section Ajout de dossier ou notes</button>
<div class="panel">
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
</div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($folderContents as $item)
        <div class="folder-card">
           @if($item["type"] == "folder")
               <a class="folder-link" href="{{route("folder_view",$item["id"])}}"><h3>[D] - {{$item["name"]}}</h3></a>
               <div class="delete">
                   <form action="{{route("delete_folder")}}" method="post">
                       <input name="id" type="hidden" value="{{$item["id"]}}"/>
                       <button type="submit">Delete</button>
                       @csrf
                   </form>
               </div>
            @else
                <a class="note-link" href="{{route("note_view",$item["id"])}}"><h3>[N] - {{$item["name"]}}</h3></a>
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



@if($folder->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <button class="accordion">Section Partage utilisateur</button>
    <div class="panel">
    <h3>Section partage utilisateur</h3>

    <p>Vous pouvez partagez ce dossier (et les notes et les dossiers qui sont à l'intérieur) à d'autre utilisateur</p>

    <div class="add-share">
        <form action="{{route("add_folder_share")}}" method="post">
            <label for="id_share">Entrez l'identifiant de la personne à qui vous souhaitez partagez la note :</label>
            <input name="id_share" type="number" min="0"/>

            <br>
            <br>
            <label for="right">Selectionnez le droit que l'utilisateur aura sur la note</label>
            <select name="right">
                <option value="RO">Lecture Seul (Read Only)</option>
                <option value="RW">Lecture et Ecriture</option>
                <option value="F">Tout (Lecture , Ecriture, Suppression, Renommer)</option>
            </select>
            <input type="hidden" name="folder_id" value="{{$folder->folder_id}}">
            <input type="submit" value="Envoyer" />
            @csrf
        </form>
    </div>

    <h3>Liste des autorisations utilisateurs</h3>

    <table>
        <thead>
        <tr>
            <th>Nom d'utilisateur</th>
            <th>ID de l'utilisateur</th>
            <th>Droit</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($usersPermissionList as $perm)
            <tr>
                <td>{{ \App\Models\User::find($perm->dest_id)->name }}</td> <!-- Remplacez 'name' par le champ correspondant dans le modèle User -->
                <td>{{ $perm->dest_id }}</td>
                <td>{{ $perm->perm }}</td>
                <td>
                    <form action="{{ route('delete_perm', ['id' => $perm->id]) }}" method="POST">
                        @csrf
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>



    </div>
@endif



<script src="/js/accordeon.js"></script>
</body>





{{dd($perm_user)}}




</html>


