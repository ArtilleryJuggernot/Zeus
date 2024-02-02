<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Zeus</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}">

</head>

@include("includes.header")
<body>

@if(session("failure"))
    <h2>{{session("failure")}}</h2>
@endif

<h1>Hello {{\Illuminate\Support\Facades\Auth::user()->name}}</h1>



<p>Bienvenue sur l'accueil faites <span class="bold">CTRL + P</span> pour accéder au menu rapide des <span class="bold">ressources</span></p>

<p>Votre identifiant est <strong>{{\Illuminate\Support\Facades\Auth::user()->id}}</strong>, vous pouvez le partagez à d'autre utilisateur pour qu'il autorise l'accès à leurs notes, dossiers, tâches et projets</p>


<div class="task-current">
    <h3>Liste des tâches actuelles à faire (avec date limite)</h3>
    <div class="folders">
        <!-- Boucle pour afficher les dossiers -->

        @if($tachesTimed->isEmpty())
            <span>Vous n'avez pas de tâche à réaliser</span>
        @endif

        @foreach($tachesTimed as $task)
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
</div>


<div class="task-current">
    <h3>Liste des tâches actuelles qui n'ont pas été réalisé</h3>
    <div class="folders">
        <!-- Boucle pour afficher les dossiers -->

        @if($tachePasse->isEmpty())
            <span>Vous n'avez pas de tâche non-réalisé</span>
        @endif

        @foreach($tachePasse as $task)
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
</div>

</body>
</html>

@include("includes.footer")
