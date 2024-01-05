@include("includes.header")
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Projet : {{$projet->name}}</title>
    <link rel="stylesheet" href="/css/folder/Overview.css"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <style>

        .progress-status{
            text-align: center;
        }
        .project-title > h3{
            text-align: center;
            font-size: 25px;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: red;
            border-radius: 4px;
            overflow: hidden; /* Cache le débordement */
            margin-bottom: 10px;
            position: relative; /* Positionnement relatif pour les éléments internes */
        }

        .progress-bg {
            height: 100%;
            width: 100%;
            position: absolute; /* Positionnement absolu pour l'arrière-plan */
            top: 0;
            left: 0;
            z-index: -1; /* Derrière la barre de progression principale */
        }

        .progress {
            height: 100%;
            transition: width 0.5s ease;
        }

        .green {
            background-color: green;
        }

        .task-todo{
            display: flex;
            flex-wrap: wrap;
        }

        .task-done{
            display: flex;
            flex-wrap: wrap;
        }

        .folder-card{
            flex: 1 0 21%; /* explanation below */
        }

    </style>
</head>
<body>

@if(session("success"))
    <h3>{{session("success")}}</h3>
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



</body>
</html>

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


    function enableDate(){
        let radio_is_due = document.getElementById("is_due").checked
        let dt_input = document.getElementById("dt_input").disabled = !radio_is_due;
    }

    document.getElementById("is_due").addEventListener("click" ,() => enableDate());

</script>




