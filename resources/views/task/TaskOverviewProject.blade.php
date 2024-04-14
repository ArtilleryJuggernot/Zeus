@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des taches</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{asset("css/notification/notification.css")}}">
</head>
<body>

<div id="notification" class="notification">
    <div class="progress"></div>
</div>


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

<button class="accordion">Ajouter des tâches</button>
<div class="panel">
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
</div>

<div class="folders-header">
    <a href="{{route("task_overview")}}"><button  class="active">Mes tâches</button> </a>
    <a href="{{route("task_overview_project")}}"> <button>Tâches dans des projets</button> </a>
</div>

<h1>Liste des tâches dans des projets</h1>
<h2>Tâches en cours : </h2>
<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($task_list_unfinish as $task)
        <div class="folder-card">
            <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>

            <div class="task-due-date">
                @if($task->due_date)
                    <p>Date limite : <span class="bold">{{$task->due_date}} </span></p>
                @endif
            </div>

            <div class="task-is-finish">
                @if($task->is_finish)
                    <p class="bold yes">Finis</p>
                @else
                    <p class="bold todo">En cours</p>
                @endif
            </div>

            <div class="task-project">
                <p>Projets associés :</p>
                <ul>
                    @foreach ($task->projects as $project)
                        <li>{{ $project->name }}</li>
                    @endforeach
                </ul>
            </div>

            <form action="{{route("UpdateTaskStatus")}}" method="POST" class="task-form">
                @csrf
                <input type="hidden" name="task_id" value="{{$task->task_id}}"> <!-- ID de la tâche -->
                <label> @if($task->is_finish) Mettre la tâche en cours @else Finir la tâche @endif
                    <input class="task-checkFinish" type="checkbox" @if($task->is_finish) checked @endif name="task_completed">
                </label>
            </form>

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

<h2>Tâches finis : </h2>
<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($task_list_finish as $task)
        <div class="folder-card">
            <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>

            <div class="task-due-date">
                @if($task->due_date)
                    <p>Date limite : <span class="bold">{{$task->due_date}} </span></p>
                @endif
            </div>

            <div class="task-is-finish">
                @if($task->is_finish)
                    <p class="bold yes">Finis</p>
                @else
                    <p class="bold todo">En cours</p>
                @endif
            </div>

            <div class="task-project">
                <p>Projets associés :</p>
                <ul>
                    @foreach ($task->projects as $project)
                        <li>{{ $project->name }}</li>
                    @endforeach
                </ul>
            </div>

            <form action="{{route("UpdateTaskStatus")}}" method="POST" class="task-form">
                @csrf
                <input type="hidden" name="task_id" value="{{$task->task_id}}"> <!-- ID de la tâche -->
                <label> @if($task->is_finish) Mettre la tâche en cours @else Finir la tâche @endif
                    <input class="task-checkFinish" type="checkbox" @if($task->is_finish) checked @endif name="task_completed">
                </label>
            </form>

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

<script src="{{asset("js/task_enable_date.js")}}"></script>

<script src="{{asset("js/task_update.js")}}"></script>


<script src="{{asset("js/accordeon.js")}}"></script>


<script src="{{asset("js/notification.js")}}"></script>


<script>
    @if(session("success"))
    showNotification("{{session("success")}}", 'success');
    @elseif(session("failure"))
    showNotification("{{session("success")}}", 'failure');
    @endif
</script>

@include("includes.footer")
