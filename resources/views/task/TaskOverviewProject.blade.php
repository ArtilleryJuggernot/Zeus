@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des taches</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
</head>

<body class="bg-gray-100">

<div id="notification" class="notification">
    <div class="progress"></div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <h2 class="text-red-500">Il y a eu des erreurs</h2>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<button class="accordion bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mb-4">Ajouter des tâches</button>
<div class="panel">
    <div class="add-task">
        <h2 class="font-bold text-xl mb-2">Ajouter une tâche :</h2>
        <form action="{{ route("store_task") }}" method="POST">
            @csrf
            <!-- Ajout du jeton CSRF pour la sécurité -->
            <label for="tache_name" class="font-bold">Nom de la tâche:</label>
            <br />
            <input type="text" id="tache_name" name="tache_name" required
                   class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3" />
            <br />
            <label for="" class="font-bold">La tâche a-t-elle une date limite ?</label>
            <br />
            <input id="is_due" type="checkbox" name="is_due" class="mr-2" />
            <input required disabled id="dt_input" type="date" name="dt_input"
                   class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 mb-3" />
            <input type="submit" value="Créer la tâche"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
        </form>
    </div>
</div>

<div class="folders-header flex justify-between items-center mb-4">
    <a href="{{ route("task_overview") }}">
        <button class="active bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mr-4">Mes tâches</button>
    </a>
    <a href="{{ route("task_overview_project") }}">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Tâches dans des projets</button>
    </a>
</div>

<h1 class="font-bold text-2xl mb-2">Liste des tâches dans des projets</h1>
<h2 class="font-bold text-xl mb-2">Tâches en cours :</h2>
<div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($task_list_unfinish as $task)
        <div class="folder-card bg-white rounded-lg p-4 shadow-md">
            <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline">
                <h3>{{ $task->task_name }}</h3>
            </a>
            <div class="task-due-date">
                @if ($task->due_date)
                    <p class="font-bold">Date limite : <span>{{ $task->due_date }}</span></p>
                @endif
            </div>
            <div class="task-is-finish">
                <p class="font-bold">{{ $task->is_finish ? 'Finis' : 'En cours' }}</p>
            </div>
            <div class="task-project">
                <p class="font-bold">Projets associés :</p>
                <ul>
                    @foreach ($task->projects as $project)
                        <li>{{ $project->name }}</li>
                    @endforeach
                </ul>
            </div>
            <form action="{{ route("UpdateTaskStatus") }}" method="POST" class="task-form">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}" />
                <!-- ID de la tâche -->
                <label class="font-bold">
                    <input type="checkbox" class="task-checkFinish mr-2" @if($task->is_finish) checked @endif
                    name="task_completed" />
                    @if ($task->is_finish)
                        Mettre la tâche en cours
                    @else
                        Finir la tâche
                    @endif
                </label>
            </form>
            <div class="delete">
                <form action="{{ route("delete_task") }}" method="post">
                    <input name="id" type="hidden" value="{{ $task->id }}" />
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                    @csrf
                </form>
            </div>
            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>

<h2 class="font-bold text-xl mb-2">Tâches finies :</h2>
<div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($task_list_finish as $task)
        <div class="folder-card bg-white rounded-lg p-4 shadow-md">
            <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline">
                <h3>{{ $task->task_name }}</h3>
            </a>
            <div class="task-due-date">
                @if ($task->due_date)
                    <p class="font-bold">Date limite : <span>{{ $task->due_date }}</span></p>
                @endif
            </div>
            <div class="task-is-finish">
                <p class="font-bold">{{ $task->is_finish ? 'Finis' : 'En cours' }}</p>
            </div>
            <div class="task-project">
                <p class="font-bold">Projets associés :</p>
                <ul>
                    @foreach ($task->projects as $project)
                        <li>{{ $project->name }}</li>
                    @endforeach
                </ul>
            </div>
            <form action="{{ route("UpdateTaskStatus") }}" method="POST" class="task-form">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}" />
                <!-- ID de la tâche -->
                <label class="font-bold">
                    <input type="checkbox" class="task-checkFinish mr-2" @if($task->is_finish) checked @endif
                    name="task_completed" />
                    @if ($task->is_finish)
                        Mettre la tâche en cours
                    @else
                        Finir la tâche
                    @endif
                </label>
            </form>
            <div class="delete">
                <form action="{{ route("delete_task") }}" method="post">
                    <input name="id" type="hidden" value="{{ $task->id }}" />
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                    @csrf
                </form>
            </div>
            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>

</body>

</html>

<script src="{{ asset("js/task_enable_date.js") }}"></script>
<script src="{{ asset("js/task_update.js") }}"></script>
<script src="{{ asset("js/accordeon.js") }}"></script>
<script src="{{ asset("js/notification.js") }}"></script>

<script>
    @if(session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session("success") }}", 'failure');
    @endif
</script>

@include("includes.footer")
