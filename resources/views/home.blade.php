<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Zeus</title>
    @livewireStyles <!-- Styles Livewire -->
</head>
@include("includes.header")
<body class="flex flex-col min-h-screen bg-gray-100">
<div id="particleCanvas"></div>
<div id="notification" class="notification">
    <div class="progress"></div>
</div>

<div class="max-w-3xl mx-auto sm:mx-0 p-6">
    <h1 class="text-3xl font-bold mb-4">
        Hello {{ Auth::user()->name }} ⚡
        <img src="{{ asset('img/thunder_anim.gif') }}" class="inline-block w-10 h-10">
    </h1>
    <p class="text-left mb-8">
        Bienvenue sur l'accueil, appuyez sur <span class="font-bold">CTRL + P</span> pour accéder au menu rapide des <span class="font-bold">ressources</span>.<br>
        Votre identifiant est <strong>{{ Auth::user()->id }}</strong>. Vous pouvez le partager avec d'autres utilisateurs pour autoriser l'accès à leurs notes, dossiers, tâches et projets.
    </p>

    <!-- Listes des tâches à faire -->
    <div class="mx-auto sm:mx-0">
        <!-- Habitude -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Liste des habitudes à faire 🏆</h2>
            <div class="flex flex-wrap">
                @forelse ($habitudes as $task)
                    <div class="basis-1/5 task-card margin-right bg-white rounded-lg shadow-md p-4">
                        <a href="{{ route('view_task', $task->id) }}" class="text-blue-500 font-bold hover:underline">
                            <h3>🏆{{ $task->task_name }}</h3>
                        </a>
                        <p class="text-red-500">⚠️ Habitude</p>
                        @if ($task->due_date)
                            <div class="task-due-date">
                                <p class="font-bold">🕐 <span>{{ $task->due_date }}</span></p>
                            </div>
                        @endif
                        <div class="task-is-finish">
                            <p class="font-bold">{{ $task->is_finish ? 'Finis' : 'En cours' }}</p>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <form action="{{ route('UpdateTaskStatus') }}" method="POST" class="task-form">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}" />
                                <label class="font-bold flex items-center">
                                    <input type="checkbox" class="task-checkFinish mr-2" @if($task->is_finish) checked @endif name="task_completed" />
                                    @if ($task->is_finish)
                                        Mettre la tâche en cours
                                    @else
                                        Finir la tâche
                                    @endif
                                </label>
                            </form>
                            <form action="{{ route('delete_task') }}" method="POST">
                                @csrf
                                <input name="id" type="hidden" value="{{ $task->id }}" />
                                <button type="submit" class="text-red-500 hover:text-red-700">❌</button>
                            </form>
                            <!-- Bouton pour modifier -->
                            <button class="text-blue-500 hover:text-blue-700" @click="openModal">🛠️</button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Vous n'avez pas de tâche habituelles à faire ✅</p>
                @endforelse
            </div>
        </div>



        <!-- Tâches prioritaires -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Liste des tâches à faire en priorité 🎯</h2>
            <div class="flex flex-wrap">
                @forelse ($task_priority as $task)
                    <livewire:task-update :taskId="$task->task_id" :priority="$task->priority" />
                @empty
                    <p class="text-gray-500">Vous n'avez pas de tâche en priorité ✅</p>
                @endforelse
            </div>
        </div>

        <!-- Tâches actuelles -->
        <div class=" mb-8">
            <h2 class="text-xl font-semibold mb-4">Liste des tâches actuelles à faire (avec date limite) 📚🕐</h2>
            <div class=" flex flex-wrap">
                @forelse ($tachesTimed as $task)
                    <div class="task-card basis-1/5 margin-right bg-white rounded-lg shadow-md">
                        <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline"><h3>{{ $task->task_name }}</h3></a>
                        @if ($task->due_date)
                            <div class="task-due-date">
                                <p class="font-bold">🕐 <span>{{ $task->due_date }}</span></p>
                            </div>
                        @endif
                        <!-- Formulaires pour mettre à jour et supprimer les tâches -->
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
                        @empty
                            <p class="text-gray-500">Vous n'avez pas de tâche à réaliser ✅</p>
                        @endforelse
                    </div>
            </div>

        </div>
        <!-- Tâches non réalisées -->
        <div class=" mb-8">
            <h2 class="text-xl font-semibold mb-4">Liste des tâches actuelles qui n'ont pas été réalisées</h2>
            <div class=" flex flex-wrap">
                @forelse ($tachePasse as $task)
                    <div class="task-card basis-1/5 margin-right bg-white rounded-lg shadow-md">
                        <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline"><h3>{{ $task->task_name }}</h3></a>
                        @if ($task->due_date)
                            <div class="task-due-date">
                                <p class="font-bold">🕐 <span>{{ $task->due_date }}</span></p>
                            </div>
                        @endif
                        <!-- Formulaires pour mettre à jour et supprimer les tâches -->
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
                    </div>
                @empty
                    <p class="text-gray-500">Vous n'avez pas de tâche non-réalisée ✅</p>

                @endforelse
            </div>
        </div>




    </div>

</div>

@include("includes.footer")
@livewireScripts <!-- Scripts Livewire -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x" defer></script>
<script>
    @if(session("success"))
    showNotification("{{ session('success') }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session('failure') }}", 'failure');
    @endif
</script>
</body>
</html>
