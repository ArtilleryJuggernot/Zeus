@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    @if($parent_content["id"] == "Racine")
        <title>Racine des dossiers - Zeus</title>
    @else
        <title>Dossier {{$folder->name}} - Zeus</title>
    @endif



    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}">
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{asset("css/category.css")}}">
    <link rel="stylesheet" href="{{asset("css/forms/formsFolder.css")}}">
</head>


<body class="background">

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
        <h3>Dossier parent : <a href="{{route("folder_view",$parent_content["id"])}}">
                {{$parent_content["name"]}}</a></h3>
        <h3>Vous êtes dans le dossier : {{$folder->name}}</h3>
    @endif
</div>

<div>

</div>


    <div class="add-section">
        <div class="add-btn" id="add-note-btn">
            <button class="add-btn-trigger">Ajouter une note 📝</button>
            <form class="add-form" action="{{route("add_note")}}" method="post">
                <label for="add-note">Entrez la note que vous souhaitez ajouter :</label>
                <input name="add-note" type="text"/>
                <input type="hidden" name="path_current" value="{{$folder_path}}">
                <input type="submit" value="Envoyer"/>
                @csrf
            </form>
        </div>

        <div class="add-btn" id="add-folder-btn">
            <button class="add-btn-trigger">Ajouter un dossier 📁</button>
            <form class="add-form" action="{{route("add_folder")}}" method="post">
                <label for="add-dossier">Entrez le nom du dossier que vous souhaitez ajouter :</label>
                <input name="add-dossier" type="text"/>
                <input type="hidden" name="path_current" value="{{$folder_path}}">
                <input type="submit" value="Envoyer"/>
                @csrf
            </form>
        </div>
    </div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->

    @if(empty($folderContents))
        <h4> > Le dossier est vide</h4>
    @endif

    @foreach($folderContents as $item)
        <div class="folder-card">
            @if($item["type"] == "folder")
                <a class="folder-link" href="{{route("folder_view",$item["id"])}}"><h3>📁 - {{$item["name"]}}</h3></a>
                <div class="delete">
                    <form action="{{route("delete_folder")}}" method="post">
                        <input name="id" type="hidden" value="{{$item["id"]}}"/>
                        <button title="Supprimer le dossier" class="del" type="submit">❌</button>
                        @csrf
                    </form>

                    <div class="list-cat">
                        @foreach($item["categories"] as $category => $id)
                            @php

                                $category = \App\Models\Categorie::find($category);
                            @endphp
                            <div class="category" style="background-color: {{ $category->color }};">
                                {{ $category->category_name }}
                            </div>
                        @endforeach
                    </div>

                </div>
            @else
                <a class="note-link" href="{{route("note_view",$item["id"])}}"><h3>📝 - {{$item["name"]}}</h3></a>
                <div class="delete">
                    <form action="{{route("delete_note")}}" method="post">
                        <input name="id" type="hidden" value="{{$item["id"]}}"/>
                        <button title="Supprimer la note" class="del" type="submit">❌</button>
                        @csrf
                    </form>

                    <div class="list-cat">
                        @foreach($item["categories"] as $category)
                            @php
                                $category = \App\Models\Categorie::find($category->categorie_id);
                            @endphp
                            <div class="category" style="background-color: {{ $category->color }};">
                                {{ $category->category_name }}
                            </div>
                        @endforeach
                    </div>

                </div>

            @endif

        </div>
    @endforeach
</div>
<div class="cat_display">

    <h2>Liste des catégories</h2>

    @foreach($ressourceCategories as $category)
        @php
            $category = \App\Models\Categorie::find($category->categorie_id);
        @endphp
        <div class="category" style="background-color: {{ $category->color }};">
            {{ $category->category_name }}
        </div>
    @endforeach
</div>

<button class="accordion">Gestion des categories</button>
<div class="panel">
    <h2>Gestion des categories</h2>
    <form method="post" action="{{ route('addCategory')}}">
        @csrf
        <label for="category">Ajouter une catégorie :</label>
        <select name="category" id="category">
            @foreach($notOwnedCategories as $categoryId => $categoryName)
                <option value="{{ $categoryId }}">{{ $categoryName }}</option>
            @endforeach
        </select>
        <input name="ressourceId" value="{{$folder->folder_id}}" type="hidden">
        <input name="ressourceType" value="folder" type="hidden">
        <button type="submit">Ajouter</button>
    </form>


    <form method="post" action="{{ route('removeCategory') }}">
        @csrf
        <label for="removeCategory">Supprimer une catégorie :</label>
        <select name="removeCategory" id="removeCategory">
            @foreach($ressourceCategories as $categoryId => $category)
                <option
                    value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
            @endforeach
        </select>
        <button type="submit">Supprimer</button>
    </form>

</div>


@if($folder->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <button class="accordion">Section Partage utilisateur</button>
    <div class="panel">
        <h3>Section partage utilisateur</h3>

        <p>Vous pouvez partagez ce dossier (et les notes et les dossiers qui sont à l'intérieur) à d'autre
            utilisateur</p>

        <div class="add-share">
            <form action="{{route("add_folder_share")}}" method="post">
                <label for="id_share">Entrez l'identifiant de la personne à qui vous souhaitez partagez la note
                    :</label>
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
                <input type="submit" value="Envoyer"/>
                @csrf
            </form>
        </div>

    </div>
    <button class="accordion">Listes des autorisations utilisateurs</button>
    <div class="panel">
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
                <td>{{ \App\Models\User::find($perm->dest_id)->name }}</td>
                <!-- Remplacez 'name' par le champ correspondant dans le modèle User -->
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


<script src="{{asset("js/accordeon.js")}}"></script>
</body>


</html>

@include("includes.footer")

