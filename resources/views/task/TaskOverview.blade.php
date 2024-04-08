@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des tâches - Zeus</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{asset("css/category.css")}}">

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

<button class="accordion">Ajouter des tâches</button>
<div class="panel">
<div class="add-task">
    <h2>Ajouter une tâche : </h2>
    <form action="{{ route('store_task') }}" method="POST">
        @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="tache_name">Nom de la tâche:</label>

        <br>

        <input minlength="1" maxlength="250" type="text" id="tache_name" name="tache_name" required>

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

<h1>Liste des tâches hors projets</h1>
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
                    <button class="del" type="submit">❌</button>
                    @csrf
                </form>
            </div>


            @if(\App\Models\Task::find($task->task_id)->owner_id == \Illuminate\Support\Facades\Auth::user()->id)

            @php
            $exist = \App\Models\task_priorities::where([
            "user_id" => \Illuminate\Support\Facades\Auth::user()->id,
            "task_id" => $task->task_id
        ])->first();

            @endphp

            <form id="priorityForm" method="POST" action="{{ route('update-priority') }}">
                @csrf
                <div class="form-group">
                    <label for="priority">Priorité :</label>
                    <select class="form-control" id="priority" name="priority" onchange="this.form.submit()">
                        <option value="">Sélectionnez une priorité</option>
                        <option @if($exist &&$exist->priority == "Urgence") selected @endif value="Urgence">Urgence</option>
                        <option @if($exist && $exist->priority == "Grande priorité") selected @endif value="Grande priorité">Grande priorité</option>
                        <option @if($exist && $exist->priority == "Prioritaire") selected @endif value="Prioritaire">Prioritaire</option>
                    </select>
                    <input name="id" type="hidden" value="{{$task->task_id}}">
                </div>
            </form>
            @endif

            <div class="list-category">
                @foreach($task["categories"] as $cat)

                    @php
                        $category = \App\Models\Categorie::find($cat->categorie_id);
                    @endphp
                    <div class="category" style="background-color: {{ $category->color }};">
                        {{ $category->category_name }}
                    </div>
                @endforeach
            </div>

        </div>
    @endforeach
</div>

<h2>Tâches finis : </h2>
<div class="folders">
    <!-- Boucle pour afficher les dossiers -->
    @foreach($task_list_finish as $task)
        <div class="folder-card">
            <a href="{{route("view_task",$task->task_id)}}"><h3>{{ $task->task_name}}</h3></a>

            @if($task->due_date)
            <div class="task-due-date">
                    <p>Date limite : <span class="bold">{{$task->due_date}} </span></p>
            </div>
            @endif

            <div class="task-is-finish">
                @if($task->is_finish)
                    <p class="bold yes">Finis</p>
                @else
                    <p class="bold todo">En cours</p>
                @endif
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
                    <button class="del" type="submit">❌</button>
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

<script src="{{asset("js/accordeon.js")}}"></script>

@include("includes.footer")
