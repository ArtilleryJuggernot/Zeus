@include("includes.header")
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Projet : {{ $projet->name }}</title>
</head>

<body class="bg-gray-100 min-h-screen font-sans text-gray-900">

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

<!-- Titre du projet -->
<div class="text-center my-6">
    <h3 class="text-3xl font-bold">🚧 Projet : {{ $projet->name }}</h3>
    <h2 class="text-2xl font-bold mt-4">📈 Progression : {{ $progression }} % ({{ count($taskFinish) }} / {{ count($taskFinish) + count($taskTODO) }})</h2>
</div>

<!-- Barre de progression -->
<div class="w-full h-8 bg-red-500 rounded-lg mb-6 relative">
    <div class="h-full bg-green-500 rounded-lg" style="width: {{ $progression }}%; transition: width 0.5s ease;"></div>
</div>

<div class="flex justify-center items-center space-x-8 mb-6">

    <!-- Formulaire pour ajouter une nouvelle tâche au projet -->
    <div x-data="{ open: false }" class="w-full max-w-md relative">
        <button @click="open = !open" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-lg flex items-center justify-center space-x-3">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M18 2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2ZM2 18V7h6.7l.4-.409A4.309 4.309 0 0 1 15.753 7H18v11H2Z"/>
                <path d="M8.139 10.411 5.289 13.3A1 1 0 0 0 5 14v2a1 1 0 0 0 1 1h2a1 1 0 0 0 .7-.288l2.886-2.851-3.447-3.45ZM14 8a2.463 2.463 0 0 0-3.484 0l-.971.983 3.468 3.468.987-.971A2.463 2.463 0 0 0 14 8Z"/>
            </svg>
            <span>Ajouter une tâche</span>
        </button>
        <div x-show="open" @click.away="open = false" class="bg-white border border-gray-300 rounded-lg shadow p-6 mt-2 absolute left-0 w-full z-10">
            <h2 class="font-bold text-xl mb-4">Ajouter une tâche au projet :</h2>
            <form action="{{ route('add_task_projet') }}" method="POST">
                @csrf
                <label for="tache_name" class="block font-bold mb-2">Nom de la tâche :</label>
                <input type="text" id="tache_name" name="tache_name" minlength="1" maxlength="250" required
                       class="border border-gray-300 rounded-md w-full py-2 px-3 mb-4 focus:outline-none focus:border-blue-500" />
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="is_due" name="is_due" class="mr-2" @click="enableDate()" />
                    <label for="is_due" class="font-bold">La tâche a-t-elle une date limite ?</label>
                </div>
                <input type="date" id="dt_input" name="dt_input" disabled
                       class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500 mb-4" />
                <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Créer la tâche</button>
            </form>
        </div>
    </div>

    <!-- Formulaire pour ajouter une tâche existante hors projet -->
    <div x-data="{ open: false }" class="w-full max-w-md relative">
        <button @click="open = !open" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 rounded-lg flex items-center justify-center space-x-3">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v3m5-3v3m5-3v3M1 7h7m1.506 3.429 2.065 2.065M19 7h-2M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm6 13H6v-2l5.227-5.292a1.46 1.46 0 0 1 2.065 2.065L8 16Z"/>
            </svg>
            <span>Ajouter une tâche hors projet</span>
        </button>
        <div x-show="open" @click.away="open = false" class="bg-white border border-gray-300 rounded-lg shadow p-6 mt-2 absolute left-0 w-full z-10">
            <h2 class="font-bold text-xl mb-4">Ajouter une tâche existante :</h2>
            <form action="{{ route('add_existing_to_project') }}" method="POST">
                @csrf
                <select name="task_id" id="task_id" required
                        class="border border-gray-300 rounded-md w-full py-2 px-3 mb-4 focus:outline-none focus:border-blue-500">
                    <option value="null" selected>Sélectionnez une tâche</option>
                    @foreach ($tasksNotInProject as $task)
                        <option value="{{ $task->id }}">{{ $task->task_name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter la tâche au projet</button>
            </form>
        </div>
    </div>

</div>


<hr class="rounded my-6">

<!-- Tâches à faire -->
<h2 class="text-center font-bold text-2xl my-4">Tâches à faire - ({{ count($taskTODO) }})</h2>
<div class="flex flex-wrap justify-center gap-4">
    @forelse ($taskTODO as $taskT)
        <livewire:task-update :taskId="$taskT->id"/>
    @empty
        <h4 class="text-xl font-semibold text-gray-700">Il n'y a actuellement pas de tâche à faire ✅</h4>
    @endforelse
</div>

<hr class="rounded my-6">

<!-- Tâches réalisées -->
<div x-data="{ open: false }" class="text-center mb-6">
    <button @click="open = !open" class="w-full md:w-1/2 lg:w-1/3 mx-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-lg">
        Tâches réalisées - ({{ count($taskFinish) }})
    </button>
    <div x-show="open" @click.away="open = false" class="flex flex-wrap justify-center gap-4 mt-4">
        @forelse ($taskFinish as $taskF)
            <div class="bg-white rounded-lg p-4 shadow-lg w-full md:w-1/3 lg:w-1/4">
                <div class="mb-2">
                    <a href="{{ route("view_task", $taskF->id) }}" class="text-blue-500 font-bold hover:underline">{{ $taskF->task_name }}</a>
                    @if ($taskF->due_date)
                        <p class="text-gray-700 font-bold">Date limite : {{ $taskF->due_date }}</p>
                    @endif
                </div>
                <div class="flex space-x-2">
                    <form action="{{ route("uncheck_task_project") }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $taskF->id }}" />
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">↩️</button>
                    </form>
                    <form action="{{ route("remove_task_from_project") }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $taskF->id }}" />
                        <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">❌</button>
                    </form>
                </div>
            </div>
        @empty
            <h4 class="text-xl font-semibold text-gray-700">Il n'y a actuellement pas de tâche réalisée ✅</h4>
        @endforelse
    </div>
</div>

<script>
    function enableDate() {
        let dt_input = document.getElementById('dt_input');
        dt_input.disabled = !dt_input.disabled;
    }
</script>

<script src="{{ asset('js/notification.js') }}"></script>
<script>
    @if(session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session("failure") }}", 'failure');
    @endif
</script>

@include("includes.footer")
</body>
</html>
