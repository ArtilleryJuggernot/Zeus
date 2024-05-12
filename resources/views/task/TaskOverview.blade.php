@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des tâches - Zeus</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
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
            <label for="tache_name" class="font-bold">Nom de la tâche:</label>
            <br />
            <input minlength="1" maxlength="250" type="text" id="tache_name" name="tache_name" required
                   class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3" />
            <br />
            <label for="" class="font-bold">La tache à-t-elle une fin limite ?</label>
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

<h1 class="font-bold text-2xl mb-2">Liste des tâches hors projets</h1>
<h2 class="font-bold text-xl mb-2">Tâches en cours :</h2>
<div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($task_list_unfinish as $task)
        <div class="task-card border-gray-500 bg-white rounded-lg shadow-md">
            <a href="{{ route("view_task", $task->id) }}" class="text-black-500 font-bold text-xl underline">
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
                    <button class="del px-5 py-2" type="submit">❌</button>
                    @csrf
                </form>
            </div>
            @if (\App\Models\Task::find($task->id)->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
                @php
                    $exist = \App\Models\task_priorities::where([
                    "user_id" => \Illuminate\Support\Facades\Auth::user()->id,
                    "task_id" => $task->id,
                    ])->first();
                @endphp

                <form id="priorityForm" method="POST" action="{{ route("update-priority") }}">
                    @csrf
                    <div class="form-group">
                        <label for="priority">Priorité :</label>
                        <select class="form-control" id="priority" name="priority"
                                onchange="this.form.submit()">
                            <option value="">
                                Sélectionnez une priorité
                            </option>
                            <option @if($exist &&$exist->priority == "Urgence") selected @endif value="Urgence">
                                Urgence
                            </option>
                            <option @if($exist && $exist->priority == "Grande priorité") selected @endif
                            value="Grande priorité">
                                Grande priorité
                            </option>
                            <option @if($exist && $exist->priority == "Prioritaire") selected @endif value="Prioritaire">
                                Prioritaire
                            </option>
                        </select>
                        <input name="id" type="hidden" value="{{ $task->id }}" />
                    </div>
                </form>
            @endif

            <div class="list-category">
                @foreach ($task["categories"] as $cat)
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


<button class="accordion bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mb-4">Tâches finis :</button>
<div class="panel">
<div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Boucle pour afficher les dossiers -->
    @foreach ($task_list_finish as $task)
        <div class="task-card bg-white rounded-lg p-4 shadow-md">
            <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline">
                <h3>{{ $task->task_name }}</h3>
            </a>
            @if ($task->due_date)
                <div class="task-due-date">
                    <p class="font-bold">Date limite : <span>{{ $task->due_date }}</span></p>
                </div>
            @endif
            <div class="task-is-finish">
                <p class="font-bold">{{ $task->is_finish ? 'Finis' : 'En cours' }}</p>
            </div>
            <div class="action-container">
            <div class="updatetask">
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
            </div>
            <div class="delete">
                <form action="{{ route("delete_task") }}" method="post">
                    <input name="id" type="hidden" value="{{ $task->id }}" />
                    <button class="del px-5 py-2" type="submit">❌</button>
                    @csrf
                </form>
            </div>
            </div>
            <!-- Autres détails du dossier si nécessaire -->
        </div>
    @endforeach
</div>
</div>
</body>

</html>

<script>
    function enableDate() {
        let radio_is_due = document.getElementById('is_due').checked;
        let dt_input = (document.getElementById('dt_input').disabled =
            !radio_is_due);
    }

    document
        .getElementById('is_due')
        .addEventListener('click', () => enableDate());
</script>

<script>
    // Sélectionnez toutes les cases à cocher avec la classe "task-checkbox"
    const checkboxes = document.querySelectorAll('.task-checkFinish');

    // Pour chaque case à cocher, ajoutez un écouteur d'événements pour détecter les changements

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function () {
            const form = checkbox.parentElement.parentElement; // Sélectionnez le formulaire correspondant
            console.log(form);
            form.submit(); // Soumettez automatiquement le formulaire lorsque la case à cocher est cochée
        });
    });
</script>

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
