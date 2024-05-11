<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Zeus</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js" data-deferred="1"></script>
</head>
@include("includes.header")
<body class="flex flex-col min-h-screen">
<div id="particleCanvas"></div>

<div id="notification" class="notification">
    <div class="progress"></div>
</div>

<!-- Contenu de la page d'accueil -->
<div class="flex flex-col items-start">
    <div class="max-w-3xl mx-auto sm:mx-4">
        <h1 class="text-3xl font-bold mb-4">
            Hello {{ \Illuminate\Support\Facades\Auth::user()->name }} ⚡
            <img src="{{ asset("img/thunder_anim.gif") }}" class="inline-block w-10 h-10">
        </h1>

        <p class="text-left mb-8">
            Bienvenue sur l'accueil, appuyez sur <span class="font-bold">CTRL + P</span> pour accéder au menu rapide des <span class="font-bold">ressources</span>.<br>
            Votre identifiant est <strong>{{ \Illuminate\Support\Facades\Auth::user()->id }}</strong>. Vous pouvez le partager avec d'autres utilisateurs pour autoriser l'accès à leurs notes, dossiers, tâches et projets.
        </p>

        <!-- Listes des tâches à faire -->
        <div class="w-[130%]  mx-auto sm:mx-0">
            <!-- Tâches prioritaires -->
            <div class="w-[130%] mb-8">
                <h2 class="text-xl font-semibold mb-4">Liste des tâches à faire en priorité</h2>
                <div class="w-[130%] flex flex-wrap">
                    @forelse ($task_priority as $task)
                        @php
                            $priority = $task->priority;
                            $task = \App\Models\Task::find($task->task_id);
                        @endphp
                        <div class="basis-1/5 task-card margin-right border-gray-500 bg-white rounded-lg shadow-md">
                            <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline"><h3>{{ $task->task_name }}</h3></a>
                            <p class="text-red-500">{{ $priority }}</p>
                            @if ($task->due_date)
                                <div class="task-due-date">
                                    <p class="font-bold">Date limite : <span>{{ $task->due_date }}</span></p>
                                </div>
                            @endif
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
                        <p class="text-gray-500">Vous n'avez pas de tâche en priorité ✅</p>
                    @endforelse
                </div>
            </div>

            <!-- Tâches actuelles -->
            <div class="w-[130%] mb-8">
                <h2 class="text-xl font-semibold mb-4">Liste des tâches actuelles à faire (avec date limite)</h2>
                <div class="w-[130%] flex flex-wrap">
                    @forelse ($tachesTimed as $task)
                        <div class="task-card basis-1/5 margin-right bg-white rounded-lg shadow-md">
                            <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline"><h3>{{ $task->task_name }}</h3></a>
                            @if ($task->due_date)
                                <div class="task-due-date">
                                    <p class="font-bold">Date limite : <span>{{ $task->due_date }}</span></p>
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
            <div class="w-[130%] mb-8">
                <h2 class="text-xl font-semibold mb-4">Liste des tâches actuelles qui n'ont pas été réalisées</h2>
                <div class="w-[130%] flex flex-wrap">
                @forelse ($tachePasse as $task)
                        <div class="task-card basis-1/5 margin-right bg-white rounded-lg shadow-md">
                            <a href="{{ route("view_task", $task->id) }}" class="text-blue-500 font-bold hover:underline"><h3>{{ $task->task_name }}</h3></a>
                            @if ($task->due_date)
                                <div class="task-due-date">
                                    <p class="font-bold">Date limite : <span>{{ $task->due_date }}</span></p>
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
                        <p class="text-gray-500">Vous n'avez pas de tâche non-réalisée ✅</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>

<script src="{{ asset("js/particules.js") }}"></script>
<script src="{{ asset("js/task_update.js") }}"></script>
<script src="{{ asset("js/notification.js") }}"></script>

<script>
    @if(session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session("success") }}", 'failure');
    @endif
</script>
@include("includes.footer")
</html>
