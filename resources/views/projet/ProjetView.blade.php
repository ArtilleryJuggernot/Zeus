@include("includes.header")
        <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Projet : {{$projet->name}}</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}">
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{asset("css/accordion.css")}}">
    <link rel="stylesheet" href="{{asset("css/category.css")}}">
    <link rel="stylesheet" href="{{asset("css/projet/View.css")}}">

    <style>
    </style>
</head>
<body>

@if(session("success"))
    <h3>{{session("success")}}</h3>
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

<div class="project-title">
    <h3>Projet : {{$projet->name}}</h3>
</div>

<h2 class="progress-status">Progression : {{$progression}} %</h2>
<div class="progress-bar">
    <div class="progress-bg"></div>
    <div class="progress green" id="progress" style="width: {{ $progression }}%;"></div>
</div>

<div class="add-task">
    <h2>Ajouter une tâche au projet : </h2>
    <form action="{{ route('add_task_projet') }}" method="POST">
        @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="tache_name">Nom de la tâche:</label>

        <br>

        <input type="text" id="tache_name" name="tache_name" required>

        <br>

        <label for="">La tache à t'elle une fin limite ?</label>
        <br>
        <input id="is_due" type="checkbox" name="is_due">
        <input required disabled id="dt_input" type="date" name="dt_input">
        <input hidden name="project_id" value="{{$projet->id}}">
        <input type="submit" value="Créer la tâche">
    </form>
</div>


<h2>Tâches à faire</h2>
<div class="task-todo">

    @if($taskTODO->isEmpty())
        <h4> > Il n'y a actuellement pas de tâche à faire</h4>
    @endif

    @foreach($taskTODO as $taskT)
        <div class="folder-card">
            <div class="info-task">
                <a href="{{route("view_task",$taskT->task_id)}}"><h3>{{ $taskT->task_name}}</h3></a>

                <span>Position de la tâche : {{$taskT->pos}}</span>
                @if($taskT->due_date)
                    <br>
                    <span>Date limite : {{$taskT->due_date}}</span>
                @endif
            </div>


            <div class="remove-from-project">
                <form action="{{route("remove_task_from_project")}}" method="post">
                    <input name="task_id" type="hidden" value="{{$taskT->task_id}}"/>
                    <input name="project_id" type="hidden" value="{{$projet->id}}">
                    <button type="submit">Supprimer la tâche du projet</button>
                    @csrf
                </form>
            </div>
            <div class="check-task">
                <form action="{{route("check_task_project")}}" method="post">
                    <input name="task_id" type="hidden" value="{{$taskT->task_id}}"/>
                    <button type="submit">Marquer la tâche comme réalisée</button>
                    @csrf
                </form>
            </div>


        </div>
    @endforeach
</div>
<h2>Tâches réalisées</h2>
<div class="task-done">


    @if($taskFinish->isEmpty())
        <h4> > Il n'y a actuellement pas de tâche réalisé</h4>
    @endif

    @foreach($taskFinish as $taskF)
        <div class="folder-card">
            <div class="info-task">
                <a href="{{route("view_task",$taskF->task_id)}}"><h3>{{ $taskF->task_name}}</h3></a>
                <span>Position de la tâche : {{$taskF->pos}}</span>
                @if($taskF->due_date)
                    <br>
                    <span>Date limite : {{$taskF->due_date}}</span>
                @endif
            </div>


            <div class="remove-from-project">
                <form action="{{route("remove_task_from_project")}}" method="post">
                    <input name="task_id" type="hidden" value="{{$taskF->task_id}}"/>
                    <input name="project_id" type="hidden" value="{{$projet->id}}">
                    <button type="submit">Supprimer la tâche du projet</button>
                    @csrf
                </form>
            </div>
            <div class="uncheck-task">
                <form action="{{route("uncheck_task_project")}}" method="post">
                    <input name="task_id" type="hidden" value="{{$taskF->task_id}}"/>
                    <button type="submit">Remettre la tâche comme "en cours"</button>
                    @csrf
                </form>
            </div>
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


@if($projet->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <button class="accordion">Gestion des partages utilisateurs</button>
    <div class="panel">
        <h1>Section partage utilisateur</h1>

        <p>Vous pouvez partagez ce projet à d'autre utilisateur</p>

        <div class="add-share">
            <form action="{{route("add_projet_share")}}" method="post">
                <label for="id_share">Entrez l'identifiant de la personne à qui vous souhaitez partagez le projet
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
                <input type="hidden" name="projet_id" value="{{$projet->id}}">
                <input type="submit" value="Envoyer"/>

                @csrf
            </form>
        </div>


    </div>

@endif


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
        <input name="ressourceId" value="{{$projet->id}}" type="hidden">
        <input name="ressourceType" value="project" type="hidden">
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

</body>

<script>
    // Récupérer la valeur de progression depuis votre backend (PHP/Blade)
    let progression = {{ $progression }};
    console.log(progression)
    let progressElement = document.getElementById('progress');

    // Mettre à jour la largeur de la barre de progression en fonction de la valeur récupérée
    progressElement.style.width = progression + '%';

    // Calculer la largeur de l'arrière-plan rouge en fonction de la progression
    let bgElement = document.querySelector('.progress-bg');
    let bgWidth = 100 - progression;
    bgElement.style.width = bgWidth + '%';


    function enableDate() {
        let radio_is_due = document.getElementById("is_due").checked
        let dt_input = document.getElementById("dt_input").disabled = !radio_is_due;
    }

    document.getElementById("is_due").addEventListener("click", () => enableDate());

</script>
<script src="{{asset("js/accordeon.js")}}"></script>
</html>






