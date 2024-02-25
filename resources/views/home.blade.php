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



<h1>Hello {{\Illuminate\Support\Facades\Auth::user()->name}} ⚡</h1>



<p>Bienvenue sur l'accueil faites <span class="bold">CTRL + P</span> pour accéder au menu rapide des <span class="bold">ressources</span></p>

<p>Votre identifiant est <strong>{{\Illuminate\Support\Facades\Auth::user()->id}}</strong>, vous pouvez le partagez à d'autre utilisateur pour qu'il autorise l'accès à leurs notes, dossiers, tâches et projets</p>




<div class="task-current">
    <h3>Liste des tâches à faire en priorité</h3>
    <div class="folders">

        @if($task_priority->isEmpty())
            <span>Vous n'avez pas de tâche en priorité ✅</span>
        @endif

        @foreach($task_priority as $task)
            @php
            $priority = $task->priority;
            $task = \App\Models\Task::find($task->task_id);
            @endphp

            <div class="folder-card">
                <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>
                <p style="color: red">{{$priority}}</p>
                @if($task->due_date)
                    <p>Tache à finir avant : </p> {{$task->due_date}}
                @endif


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
                        <button class="del" type="submit">Delete</button>
                        @csrf
                    </form>
                </div>
            </div>


                @endforeach
    </div>


</div>

<div class="task-current">
    <h3>Liste des tâches actuelles à faire (avec date limite)</h3>
    <div class="folders">
        <!-- Boucle pour afficher les dossiers -->

        @if($tachesTimed->isEmpty())
            <span>Vous n'avez pas de tâche à réaliser ✅</span>
        @endif

        @foreach($tachesTimed as $task)
            <div class="folder-card">
                <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>
                @if($task->due_date)
                    <p>Tache à finir avant : </p> {{$task->due_date}}
                @endif

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
                        <button class="del" type="submit">Delete</button>
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
            <span>Vous n'avez pas de tâche non-réalisé ✅</span>
        @endif

        @foreach($tachePasse as $task)
            <div class="folder-card">
                <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>
                @if($task->due_date)
                    <p>Tache à finir avant : </p> {{$task->due_date}}
                @endif

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
                        <button class="del" type="submit">Delete</button>
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


<script>
    // Sélectionnez toutes les cases à cocher avec la classe "task-checkbox"
    const checkboxes = document.querySelectorAll(".task-checkFinish");

    // Pour chaque case à cocher, ajoutez un écouteur d'événements pour détecter les changements

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const form = checkbox.parentElement.parentElement // Sélectionnez le formulaire correspondant
            console.log(form)
            form.submit(); // Soumettez automatiquement le formulaire lorsque la case à cocher est cochée
        });
    });
</script>
@include("includes.footer")
