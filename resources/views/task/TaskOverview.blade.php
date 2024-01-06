@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des taches</title>
    <link rel="stylesheet" href="/css/folder/Overview.css"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
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

<div class="add-task">
    <h2>Ajouter une tâche : </h2>
    <form action="{{ route('store_task') }}" method="POST">
        @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="tache_name">Nom de la tâche:</label>

        <br>

        <input type="text" id="tache_name" name="tache_name" required>

        <br>

        <label for="">La tache à t'elle une fin limite ?</label>
        <br>
        <input id="is_due" type="checkbox" name="is_due">
        <input required disabled id="dt_input" type="date" name="dt_input">

        <input type="submit" value="Créer la tâche">
    </form>
</div>

<div class="folders-header">
    <button class="active">Mes tâches</button>
    <button>Tâches dans des projets</button>
</div>
<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($task_list as $task)
        <div class="folder-card">
            <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>
            @if($task->due_date)
                <p>Tache à finir avant : </p> {{$task->due_date}}
            @endif
            <div class="delete">
                <form action="{{route("delete_task")}}" method="post">
                    <input name="id" type="hidden" value="{{$task->task_id}}"/>
                    <button type="submit">Delete</button>
                    @csrf
                </form>
            </div>

            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>
</body>

</html>

<script>
    function enableDate(){
        let radio_is_due = document.getElementById("is_due").checked
        let dt_input = document.getElementById("dt_input").disabled = !radio_is_due;
    }

    document.getElementById("is_due").addEventListener("click" ,() => enableDate());

</script>
