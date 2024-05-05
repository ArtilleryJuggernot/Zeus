@include('includes.header')

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editeur de Tâche - {{$task->task_name}} - Zeus</title>
    <link rel="stylesheet" href="{{asset("css/category.css")}}">
    <link rel="stylesheet" href="{{asset("css/tableau.css")}}">
    <link rel="stylesheet" href="{{asset("css/accordion.css")}}">
    <link rel="stylesheet" href="{{asset("css/note/editor.css")}}">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="{{asset("css/notification/notification.css")}}">
    <script type="module" src="{{asset("js/stack_edit/stack_edit_task.js")}}"></script>

</head>
<body>


<script>
    var content = {!! json_encode($task->description) !!};
    @if(\Illuminate\Support\Facades\Auth::user()->id == $task->owner_id)
        const perm = "F"; // L'utilisateur propriétaire à tout les droits
    @else
        const perm = "{{$perm_user->perm }}";
    @endif

    const csrf = '{{csrf_token()}}';
    const task_id =  '{{ $task->task_id}}';
    const user_id = '{{\Illuminate\Support\Facades\Auth::user()->id}}';
</script>

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

<div id="notification" class="notification">
    <div class="progress"></div>
</div>


<h1 class="center">Editeur de Tâche - {{$task->task_name}}</h1>

@if($task->owner_id != \Illuminate\Support\Facades\Auth::user()->id)

    <h3 class="it">Vous êtes sur la tâche de {{\App\Models\User::find($task->owner_id)->name}}</h3>
    <h3 class="it">Vous avez des droits de :
    @if($perm_user == "F")
        Total
        @else
            @if($perm_user->perm == "RO") Lecture Seule
            @elseif($perm_user->perm == "RW") Lecture et Ecriture
            @elseif($perm_user->perm == "F" ) Total
            sur cette note
            @endif
    @endif
    </h3>


@endif

<div class="center">
    <label>La tâche est t'elle fini ?</label>
    <input id="is_finish" type="checkbox" @if($task->is_finish) checked @endif name="is_finish">
</div>

<div id="editor_md">

</div>

<!--
<div class="note-editor">

    <textarea id="note-content" rows="10" cols="10">{{$task->description}}</textarea>


    <div id="preview"></div>
</div>

!-->



<div class="allign center ">
    <button class="space_btn" onclick="saveTask()"><span class="emoji">💾 </span> Sauvegarder la tache</button>

<div class="delete">
    <form action="{{route("delete_task")}}" method="post">
        <input name="id" type="hidden" value="{{$task->task_id}}"/>
        <button class="space_btn" type="submit"><span class="emoji">🗑</span>️ Supprimer la tâche</button>
        @csrf
    </form>
</div>
</div>
<span class="center">Attention ! Supprimer une tâche implique qu'elle sera supprimé dans tout les projets</span>



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
    <form method="post" action="{{ route('addCategory') }}">
        @csrf
        <label for="category">Ajouter une catégorie :</label>
        <select name="category" id="category">
            @foreach($notOwnedCategories as $categoryId => $categoryName)
                <option value="{{ $categoryId }}">{{ $categoryName }}</option>
            @endforeach
        </select>
        <input name="ressourceId" value="{{$task->task_id}}" type="hidden">
        <input name="ressourceType" value="task" type="hidden">
        <button type="submit">Ajouter</button>
    </form>


    <form method="post" action="{{ route('removeCategory') }}">
        @csrf
        <label for="removeCategory">Supprimer une catégorie :</label>
        <select name="removeCategory" id="removeCategory">
            @foreach($ressourceCategories as $categoryId => $category)
                <option value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
            @endforeach
        </select>
        <button type="submit">Supprimer</button>
    </form>

</div>


@if($task->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
<button class="accordion">Gestion des partages utilisateurs</button>
<div class="panel">




    <h1>Section partage utilisateur</h1>

    <p>Vous pouvez partagez cette tâche à d'autre utilisateur</p>

    <div class="add-share">
        <form action="{{route("add_task_share")}}" method="post">
            <label for="id_share">Entrez l'identifiant de la personne à qui vous souhaitez partagez la tache :</label>
            <input name="id_share" type="number" min="0"/>
            <br>
            <br>
            <label for="right">Selectionnez le droit que l'utilisateur aura sur la note</label>
            <select name="right">
                <option value="RO">Lecture Seul (Read Only)</option>
                <option value="RW">Lecture et Ecriture</option>
                <option value="F">Tout (Lecture , Ecriture, Suppression, Renommer)</option>
            </select>
            <input type="hidden" name="task_id" value="{{$task->task_id}}">
            <input type="submit" value="Envoyer" />

            @csrf
        </form>
    </div>
    </div>

<button class="accordion">Liste des autorisations utilisateurs</button>
<div class="panel">
    <h1>Liste des autorisations utilisateurs</h1>

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
@endif
</div>




<script src="{{asset("js/accordeon.js")}}"></script>

<!-- Ajoutez ce code dans votre vue HTML -->

<script src="{{asset("js/notification.js")}}"></script>


<script>
    @if(session("success"))
    showNotification("{{session("success")}}", 'success');
    @elseif(session("failure"))
    showNotification("{{session("success")}}", 'failure');
    @endif
</script>

<script src="{{asset("js/shortcut_editor.js")}}"></script>





</body>
</html>


@include("includes.footer")
