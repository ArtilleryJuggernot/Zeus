@include("includes.header")
    <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Projet : {{ $projet->name }}</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/projet/View.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
    <style></style>
</head>

<body class="bg-gray-100">

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

<div class="project-title font-bold">
    <h3 class="text-center text-3xl">🚧 Projet : {{ $projet->name }}</h3>
</div>

<h2 class="progress-status text-center font-bold mb-4 mt-4 text-2xl">
    📈 Progression : {{ $progression }} % ({{ count($taskFinish) }} /
    {{ count($taskFinish) + count($taskTODO) }})
</h2>
<div class="progress-bar bg-red-500 w-full h-8 rounded-lg mb-4 relative">
    <div class="progress-bg h-full bg-green-500"></div>
    <div class="progress green h-full" id="progress" style="width: {{ $progression }}%;"></div>
</div>


<div class="flex justify-center">

<button class="accordion mb-4">
    <div class="text-center justify-center m-auto">
        <div class="text-center m-auto table ">
        <svg class="w-10 h-10 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M18 2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2ZM2 18V7h6.7l.4-.409A4.309 4.309 0 0 1 15.753 7H18v11H2Z"/>
        <path d="M8.139 10.411 5.289 13.3A1 1 0 0 0 5 14v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .7-.288l2.886-2.851-3.447-3.45ZM14 8a2.463 2.463 0 0 0-3.484 0l-.971.983 3.468 3.468.987-.971A2.463 2.463 0 0 0 14 8Z"/>
        </svg>
        </div>
        <span class="font-bold text-black text-xl">Ajouter une tâche</span>
    </div>
</button>
<div class=" panel add-task max-w-md mb-4">
    <h2 class="text-xl font-bold text-xl">Ajouter une tâche au projet :</h2>
    <form action="{{ route("add_task_projet") }}" method="POST">
        @csrf
        <!-- Ajout du jeton CSRF pour la sécurité -->
        <label for="tache_name">Nom de la tâche:</label>
        <br />
        <input minlength="1" maxlength="250" type="text" id="tache_name" name="tache_name" required
               class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3" />
        <br />

        <input id="is_due" type="checkbox" name="is_due" />
        <label for="is_due">La tache à t'elle une fin limite ?</label>
        <input required disabled id="dt_input" type="date" name="dt_input"
               class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3" />
        <input hidden name="project_id" value="{{ $projet->id }}" />
        <input type="submit" value="Créer la tâche"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
    </form>
</div>


<button class="accordion  mb-4">
    <div class="text-center justify-center m-auto">
        <div class="text-center m-auto table">
            <svg class="w-10 h-10 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v3m5-3v3m5-3v3M1 7h7m1.506 3.429 2.065 2.065M19 7h-2M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm6 13H6v-2l5.227-5.292a1.46 1.46 0 0 1 2.065 2.065L8 16Z"/>
            </svg>
        </div>
        <span class="font-bold text-black text-xl">Ajouter une tâche hors projet</span>
    </div>

</button>
<div class=" panel add-task-already-created max-w-md mb-4 ">
    <h2 class="font-bold text-xl">Rajouter une tâche hors-projet dans ce projet</h2>
    <form action="{{ route("add_existing_to_project") }}" method="post">
        <select name="task_id" id="task_id" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3">
            <option name="task_id" selected value="null">Sélectionnez une tâche</option>
            @foreach ($tasksNotInProject as $task)
                <option name="task_id" value="{{ $task->id }}">{{ $task->task_name }}</option>
            @endforeach
        </select>
        <input type="hidden" name="project_id" value="{{ $projet->id }}" />
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">Ajouter la tâche au projet</button>
        @csrf
    </form>
</div>


</div>

<hr class="rounded">

<h2 class=" mb-4 text-2xl text-center font-bold">Tâches à faire - ({{ count($taskTODO) }})</h2>
<div class="task-todo flex flex-wrap justify-around">
    @if ($taskTODO->isEmpty())
        <h4 class="text-2xl">Il n'y a actuellement pas de tâche à faire ✅</h4>
    @endif
    @foreach ($taskTODO as $taskT)
        <div class="folder-card bg-white rounded-lg p-4 shadow-md mb-4">
            <div class="info-task">
                <a href="{{ route("view_task", $taskT->id) }}" class="text-blue-500 font-bold hover:underline">
                    <h3>{{ $taskT->task_name }}</h3>
                </a>
                <span class="font-bold">Position de la tâche : {{ $taskT->pos }}</span>
                @if ($taskT->due_date)
                    <br />
                    <span class="font-bold">Date limite : {{ $taskT->due_date }}</span>
                @endif
            </div>
            <form action="{{ route("UpdateTaskStatus") }}" method="POST" class="task-form">
                @csrf
                <input type="hidden" name="task_id" value="{{ $taskT->id }}" />
                <label class="font-bold">
                    @if ($taskT->is_finish)
                        Mettre la tâche en cours
                    @else
                        Finir la tâche
                    @endif
                    <input class="task-checkFinish" type="checkbox" @if($taskT->is_finish) checked @endif
                    name="task_completed" />
                </label>
            </form>


            <div class="flex pb-5">


            <div class="remove-from-project">
                <form action="{{ route("remove_task_from_project") }}" method="post">
                    <input name="task_id" type="hidden" value="{{ $taskT->id }}" />
                    <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                    <button title="Supprimer la tâche du projet" class="del px-5 py-2" type="submit">❌</button>
                    @csrf
                </form>
            </div>
            <div class="check-task">
                <form action="{{ route("check_task_project") }}" method="post">
                    <input name="task_id" type="hidden" value="{{ $taskT->id }}" />
                    <button class="px-5 py-2 bg-green-500" title="Marquer la tâche comme terminé" type="submit">✅</button>
                    @csrf
                </form>
            </div>
            </div>



            <div class="check-task">
                <form action="{{ route("unlink_task_from_project") }}" method="post">
                    <input name="task_id" type="hidden" value="{{ $taskT->id }}" />
                    <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                    <button class="del px-5 py-2" type="submit">Dissocier la tâche du projet</button>
                    @csrf
                </form>
            </div>
        </div>
    @endforeach
</div>

<button class="accordion mb-4 text-xl font-bold text-black ">Tâches réalisées - ({{ count($taskFinish) }})</button>
<div class="panel">
    <div class="task-done flex flex-wrap justify-around">
        @if ($taskFinish->isEmpty())
            <h4 class="text-2xl mb-5">Il n'y a actuellement pas de tâche réalisé ✅</h4>
        @endif
        @foreach ($taskFinish as $taskF)
            <div class="folder-card bg-white rounded-lg p-4 shadow-md mb-4">
                <div class="info-task">
                    <a href="{{ route("view_task", $taskF->id) }}" class="text-blue-500 font-bold hover:underline">
                        <h3>{{ $taskF->task_name }}</h3>
                    </a>
                    @if ($taskF->due_date)
                        <span>Date limite : {{ $taskF->due_date }}</span>
                    @endif
                </div>
                <form action="{{ route("UpdateTaskStatus") }}" method="POST" class="task-form">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $taskF->id }}" />
                    <label>
                        @if ($taskF->is_finish)
                            Mettre la tâche en cours
                        @else
                            Finir la tâche
                        @endif
                        <input class="task-checkFinish" type="checkbox" @if($taskF->is_finish) checked @endif
                        name="task_completed" />
                    </label>
                </form>
                <div class="remove-from-project">
                    <form action="{{ route("remove_task_from_project") }}" method="post">
                        <input name="task_id" type="hidden" value="{{ $taskF->id }}" />
                        <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                        <button class="del px-5 py-2" type="submit">Supprimer la tâche du projet</button>
                        @csrf
                    </form>
                </div>
                <div class="uncheck-task">
                    <form action="{{ route("uncheck_task_project") }}" method="post">
                        <input name="task_id" type="hidden" value="{{ $taskF->id }}" />
                        <button type="submit">Remettre la tâche comme "en cours"</button>
                        @csrf
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<hr class="rounded">

<div class="cat_display mb-4">
    <h2 class="text-center text-2xl mt-3">Liste des catégories</h2>
    <div class="flex justify-center flex-wrap">
        @foreach ($ressourceCategories as $category)
            @php
                $category = \App\Models\Categorie::find($category->categorie_id);
            @endphp
            <div class="category text-center py-2 px-4 m-2 rounded-lg" style="background-color: {{ $category->color }}">
                {{ $category->category_name }}
            </div>
        @endforeach
    </div>
</div>

<button class="accordion text-center mb-4 font-bold text-black text-xl">Liste des autorisations utilisateurs</button>
<div class="panel">
    <h1 class="text-center mb-4">Liste des autorisations utilisateurs</h1>
    <div class="overflow-x-auto">
        <table class="table-auto mx-auto">
            <thead>
            <tr>
                <th class="px-4 py-2">Nom d'utilisateur</th>
                <th class="px-4 py-2">ID de l'utilisateur</th>
                <th class="px-4 py-2">Droit</th>
                <th class="px-4 py-2">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($usersPermissionList as $perm)
                <tr>
                    <td class="border px-4 py-2">{{ \App\Models\User::find($perm->dest_id)->name }}</td>
                    <td class="border px-4 py-2">{{ $perm->dest_id }}</td>
                    <td class="border px-4 py-2">{{ $perm->perm }}</td>
                    <td class="border px-4 py-2">
                        <form action="{{ route("delete_perm", ["id" => $perm->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@if ($projet->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <button class="accordion text-center mb-4 font-bold text-black text-xl">Gestion des partages utilisateurs</button>
    <div class="panel">
        <h1 class="text-center mb-4">Section partage utilisateur</h1>
        <p class="text-center mb-4">Vous pouvez partager ce projet avec d'autres utilisateurs</p>
        <div class="max-w-md mx-auto mb-4">
            <form action="{{ route("add_projet_share") }}" method="post">
                <label for="id_share" class="block mb-2">Entrez l'identifiant de la personne à qui vous souhaitez partager le projet :</label>
                <input name="id_share" type="number" min="0" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3" />
                <label for="right" class="block mb-2">Sélectionnez le droit que l'utilisateur aura sur le projet :</label>
                <select name="right" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full mb-3">
                    <option value="RO">Lecture Seul (Read Only)</option>
                    <option value="RW">Lecture et Ecriture</option>
                    <option value="F">Tout (Lecture, Ecriture, Suppression, Renommer)</option>
                </select>
                <input type="hidden" name="projet_id" value="{{ $projet->id }}" />
                <input type="submit" value="Envoyer" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" />
                @csrf
            </form>
        </div>
    </div>
@endif

<button class="accordion text-center mb-4 font-bold text-black text-xl">Gestion des catégories</button>
<div class="panel">
    <h2 class="text-center mb-4">Gestion des catégories</h2>
    <form method="post" action="{{ route("addCategory") }}" class="mb-4">
        @csrf
        <label for="category" class="block mb-2">Ajouter une catégorie :</label>
        <select name="category" id="category" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 mb-3">
            @foreach ($notOwnedCategories as $categoryId => $categoryName)
                <option value="{{ $categoryId }}">{{ $categoryName }}</option>
            @endforeach
        </select>
        <input name="ressourceId" value="{{ $projet->id }}" type="hidden" />
        <input name="ressourceType" value="project" type="hidden" />
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</button>
    </form>

    <form method="post" action="{{ route("removeCategory") }}">
        @csrf
        <label for="removeCategory" class="block mb-2">Supprimer une catégorie :</label>
        <select name="removeCategory" id="removeCategory" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 mb-3">
            @foreach ($ressourceCategories as $categoryId => $category)
                <option value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
    </form>
</div>

</body>

<script>
    // Récupérer la valeur de progression depuis votre backend (PHP/Blade)
    let progression = {{ $progression }};
    console.log(progression);
    let progressElement = document.getElementById('progress');

    // Mettre à jour la largeur de la barre de progression en fonction de la valeur récupérée
    progressElement.style.width = progression + '%';

    // Calculer la largeur de l'arrière-plan rouge en fonction de la progression
    let bgElement = document.querySelector('.progress-bg');
    let bgWidth = 100 - progression;
    bgElement.style.width = bgWidth + '%';
</script>
<script src="{{ asset("js/task_enable_date.js") }}"></script>
<script src="{{ asset("js/accordeon.js") }}"></script>
<script src="{{ asset("js/task_update.js") }}"></script>

<script src="{{ asset("js/notification.js") }}"></script>

<script>
    @if(session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session("success") }}", 'failure');
    @endif
</script>

</html>

@include("includes.footer")
