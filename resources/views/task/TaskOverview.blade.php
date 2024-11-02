@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des tâches - Zeus</title>
</head>

<body class="bg-gray-100 min-h-screen font-sans text-gray-900">

<!-- Notification -->
<div id="notification" class="fixed top-24 right-6 bg-green-500 text-white font-bold py-2 px-4 rounded transition-opacity duration-300 opacity-0">
    <div class="progress h-1 bg-white rounded-full"></div>
</div>

<!-- Erreurs -->
@if ($errors->any())
    <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 mb-6 mx-4 rounded">
        <h2 class="font-bold mb-2">Il y a eu des erreurs</h2>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Formulaire d'ajout de tâche -->
<div x-data="{ open: false }" class="mb-6">
    <button @click="open = !open" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mb-4 w-full text-left">
        Ajouter des tâches
    </button>
    <div x-show="open" @click.away="open = false" class="p-4 bg-white border border-gray-300 rounded shadow-lg mt-2">
        <h2 class="font-bold text-xl mb-4">Ajouter une tâche :</h2>
        <form action="{{ route("store_task") }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="tache_name" class="font-bold">Nom de la tâche :</label>
                <input minlength="1" maxlength="250" type="text" id="tache_name" name="tache_name" required
                       class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 w-full" />
            </div>

            <div class="flex items-center">
                <label for="is_due" class="font-bold mr-2">La tâche a-t-elle une date limite ?</label>
                <input id="is_due" type="checkbox" name="is_due" class="mr-2" />
                <input required disabled id="dt_input" type="date" name="dt_input"
                       class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" />
            </div>

            <div>
                <label for="priority" class="font-bold">Priorité :</label>
                <select id="priority" name="priority" class="w-full border border-gray-300 rounded-md py-2 px-3">
                    <option value="None">Aucune</option>
                    <option value="Urgence">Urgence</option>
                    <option value="Grande priorité">Grande priorité</option>
                    <option value="Prioritaire">Prioritaire</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Créer la tâche</button>
        </form>
    </div>
</div>

<!-- Liste des tâches en cours -->
<h1 class="font-bold text-2xl my-4">Liste des tâches hors projets</h1>
<h2 class="font-bold text-xl mb-4">Tâches en cours :</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($task_list_unfinish as $task)
        <livewire:task-update :taskId="$task->id"/>
    @endforeach
</div>

<!-- Liste des tâches terminées -->
<div x-data="{ open: false }" class="mt-6">
    <button @click="open = !open" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg w-full text-left">
        Tâches finies
    </button>
    <div x-show="open" @click.away="open = false" class="p-4 bg-white border border-gray-300 rounded shadow-lg mt-2">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($task_list_finish as $task)
                <livewire:task-update :taskId="$task->id"/>
            @endforeach
        </div>
    </div>
</div>

<script>
    function enableDate() {
        let radio_is_due = document.getElementById('is_due').checked;
        document.getElementById('dt_input').disabled = !radio_is_due;
    }
    document.getElementById('is_due').addEventListener('click', enableDate);
</script>

<script src="{{ asset('js/notification.js') }}"></script>

<script>
    @if(session("success"))
    showNotification("{{ session('success') }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session('failure') }}", 'failure');
    @endif
</script>

@include("includes.footer")
</body>
</html>
