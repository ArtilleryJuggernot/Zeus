@include("includes.header")
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>🚧 Projet : {{ $projet->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .badge { display: inline-block; padding: 0.25em 0.75em; border-radius: 9999px; font-size: 0.9em; font-weight: bold; }
        .badge-en-cours { background: #fbbf24; color: #fff; }
        .badge-termine { background: #22c55e; color: #fff; }
        .badge-pause { background: #64748b; color: #fff; }
        .emoji { font-size: 1.3em; vertical-align: middle; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-green-50 min-h-screen text-gray-900">

<!-- Notification -->
<div id="notification" class="fixed top-24 right-6 bg-green-500 text-white font-bold py-2 px-4 rounded shadow-lg transition-opacity duration-300 opacity-0 z-50 flex items-center space-x-2">
    <span id="notif-emoji">✅</span>
    <span id="notif-text"></span>
</div>

<!-- Erreurs -->
@if ($errors->any())
    <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 mb-6 mx-4 rounded shadow">
        <h2 class="font-bold mb-2">❌ Il y a eu des erreurs</h2>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Titre du projet et progression -->
<div class="text-center my-8">
    <h1 class="text-4xl font-extrabold mb-2 flex items-center justify-center gap-2">🚧 Projet : <span class="text-blue-600">{{ $projet->name }}</span></h1>
    <div class="flex items-center justify-center gap-4 mt-2">
        <span class="badge badge-en-cours">🟡 En cours</span>
        <span class="text-xl font-bold">📈 Progression : <span class="text-green-600">{{ $progression }}%</span> ({{ count($taskFinish) }} / {{ count($taskFinish) + count($taskTODO) }})</span>
    </div>
    <div class="w-full max-w-2xl mx-auto h-6 bg-gray-300 rounded-full mt-6 relative shadow">
        <div class="h-full bg-gradient-to-r from-green-400 to-blue-500 rounded-full" style="width: {{ $progression }}%; transition: width 0.5s ease;"></div>
        <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-sm font-bold text-gray-700">{{ $progression }}%</span>
    </div>
</div>

<!-- Actions principales -->
<div class="flex flex-col md:flex-row justify-center items-stretch gap-8 mb-10 px-4">
    <!-- Ajouter une nouvelle tâche -->
    <div x-data="{ open: false }" class="w-full max-w-md relative">
        <button @click="open = !open" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-3 shadow-lg transition">
            <span class="emoji">➕</span> <span>Ajouter une tâche</span>
        </button>
        <div x-show="open" @click.away="open = false" class="bg-white border border-gray-200 rounded-xl shadow-xl p-6 mt-2 absolute left-0 w-full z-20 animate-fade-in">
            <h2 class="font-bold text-xl mb-4 flex items-center gap-2">📝 Ajouter une tâche au projet</h2>
            <form action="{{ route('add_task_projet') }}" method="POST" class="space-y-4">
                @csrf
                <label for="tache_name" class="block font-bold">Nom de la tâche :</label>
                <input type="text" id="tache_name" name="tache_name" minlength="1" maxlength="250" required class="border border-gray-300 rounded-lg w-full py-2 px-3 focus:outline-none focus:border-blue-500" placeholder="Ex : Préparer la réunion" />
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_due" name="is_due" class="mr-2 accent-blue-600" @click="enableDate()" />
                    <label for="is_due" class="font-bold">La tâche a-t-elle une date limite ? <span class="emoji">📅</span></label>
                </div>
                <input type="date" id="dt_input" name="dt_input" disabled class="border border-gray-300 rounded-lg w-full py-2 px-3 focus:outline-none focus:border-blue-500" />
                <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-xl flex items-center justify-center gap-2 transition">✅ Créer la tâche</button>
            </form>
        </div>
    </div>
    <!-- Ajouter une tâche existante -->
    <div x-data="{ open: false }" class="w-full max-w-md relative">
        <button @click="open = !open" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-3 shadow-lg transition">
            <span class="emoji">🗂️</span> <span>Ajouter une tâche hors projet</span>
        </button>
        <div x-show="open" @click.away="open = false" class="bg-white border border-gray-200 rounded-xl shadow-xl p-6 mt-2 absolute left-0 w-full z-20 animate-fade-in">
            <h2 class="font-bold text-xl mb-4 flex items-center gap-2">🔗 Ajouter une tâche existante</h2>
            <form action="{{ route('add_existing_to_project') }}" method="POST" class="space-y-4">
                @csrf
                <select name="task_id" id="task_id" required class="border border-gray-300 rounded-lg w-full py-2 px-3 focus:outline-none focus:border-blue-500">
                    <option value="null" selected>Sélectionnez une tâche</option>
                    @foreach ($tasksNotInProject as $task)
                        <option value="{{ $task->id }}">{{ $task->task_name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-xl flex items-center justify-center gap-2 transition">➕ Ajouter la tâche au projet</button>
            </form>
        </div>
    </div>
</div>

<hr class="rounded my-8 border-2 border-blue-200">

<!-- Tâches à faire -->
<div x-data="taskEditModal()">
    <h2 class="text-center font-bold text-2xl my-6 flex items-center justify-center gap-2">📝 Tâches à faire <span class="badge badge-en-cours">{{ count($taskTODO) }}</span></h2>
    <!-- Boutons de tri -->
    <div class="flex justify-center gap-4 mb-6">
        <a href="{{ route('projet_view', ['id' => $projet->id, 'sort' => 'position']) }}" class="px-4 py-2 rounded-lg font-bold border transition {{ $sort === 'position' ? 'bg-blue-500 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-300 hover:bg-blue-50' }}">
            Trier par position
        </a>
        <a href="{{ route('projet_view', ['id' => $projet->id, 'sort' => 'date_asc']) }}" class="px-4 py-2 rounded-lg font-bold border transition {{ $sort === 'date_asc' ? 'bg-blue-500 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-300 hover:bg-blue-50' }}">
            Plus ancien d'abord
        </a>
        <a href="{{ route('projet_view', ['id' => $projet->id, 'sort' => 'date_desc']) }}" class="px-4 py-2 rounded-lg font-bold border transition {{ $sort === 'date_desc' ? 'bg-blue-500 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-300 hover:bg-blue-50' }}">
            Plus récent d'abord
        </a>
    </div>
    <div class="flex flex-wrap justify-center gap-6">
        @forelse ($taskTODO as $taskT)
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col gap-3 hover:scale-105 hover:shadow-2xl transition-all duration-200 animate-fade-in relative w-full md:w-80">
                <div class="flex items-center justify-between mb-2">
                    <a href="{{ route('view_task', $taskT->id) }}" class="font-bold text-lg text-blue-600 hover:underline transition cursor-pointer flex-1 text-left">{{ $taskT->task_name }}</a>
                    @php
                        $prio = \App\Models\task_priorities::where('task_id', $taskT->id)->first();
                    @endphp
                    @if($prio)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-pink-200 to-yellow-200 text-pink-700 animate-pulse shadow">{{ $prio->priority }}</span>
                    @endif
                    <!-- Bouton éditer -->
                    <button @click="openEditModal({
                        id: {{ $taskT->id }},
                        name: @js($taskT->task_name),
                        due_date: @js($taskT->due_date),
                        priority: @js(optional($prio)->priority ?? 'None'),
                        categories: @js($taskT->categories->pluck('categorie_id')->toArray())
                    })" class="ml-2 p-2 rounded-full hover:bg-blue-100 transition" title="Éditer la tâche">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </button>
                </div>
                <!-- Affichage de la position -->
                <div class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <span class="font-semibold">Position :</span> <span>{{ $taskT->pos ?? '-' }}</span>
                </div>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach($taskT->categories as $cat)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#3b82f6' }}; color: white;">{{ $cat->category_name }}</span>
                    @endforeach
                </div>
                @if($taskT->due_date)
                    <div class="mt-2 text-sm text-pink-600 font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::parse($taskT->due_date)->format('d/m/Y') }}
                    </div>
                @endif
                <div class="flex justify-between items-center gap-4 mt-4">
                    <form action="{{ route('check_task_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $taskT->id }}" />
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Valider">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            Valider
                        </button>
                    </form>
                    <form action="{{ route('remove_task_from_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $taskT->id }}" />
                        <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Supprimer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <h4 class="text-xl font-semibold text-gray-700">Il n'y a actuellement pas de tâche à faire <span class="emoji">✅</span></h4>
        @endforelse

        <!-- Modale globale d'édition -->
        <template x-if="editModalOpen">
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black bg-opacity-40" @click="closeEditModal()"></div>
                <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative animate-fade-in border border-blue-200 z-10"
                     x-effect="if(editModalOpen){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }">
                    <button @click="closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                    <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Éditer la tâche
                    </h3>
                    <form :action="editFormAction" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="task_id" :value="editTask.id">
                        <div>
                            <label class="font-bold flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                                </svg>
                                Nom de la tâche :
                            </label>
                            <input type="text" name="tache_name" x-model="editTask.name" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="font-bold flex items-center gap-2">
                                <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Date limite ?
                            </label>
                            <input type="checkbox" name="is_due" value="on" x-model="editTask.has_due" class="accent-pink-500 w-5 h-5" />
                            <input type="date" name="dt_input" :disabled="!editTask.has_due" x-model="editTask.due_date" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition w-1/2" />
                        </div>
                        <div>
                            <label class="font-bold flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                Priorité :
                            </label>
                            <select name="priority" x-model="editTask.priority" class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-purple-400">
                                <option value="None">Aucune</option>
                                <option value="Urgence">🔥 Urgence</option>
                                <option value="Grande priorité">⚡ Grande priorité</option>
                                <option value="Prioritaire">⭐ Prioritaire</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-bold flex items-center gap-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                </svg>
                                Catégories :
                            </label>
                            <input type="text" placeholder="Rechercher une catégorie..." x-model="categorySearch" class="mb-2 w-full px-3 py-1 rounded border border-blue-200 focus:ring-2 focus:ring-blue-400 text-sm bg-blue-50 placeholder-blue-300" />
                            <div class="max-h-40 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col gap-2">
                                <template x-for="cat in filteredCategories()" :key="cat.category_id">
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-blue-50 rounded px-2 py-1 transition">
                                        <span class="w-4 h-4 rounded-full inline-block" :style="'background: ' + (cat.color ?? '#3b82f6') + ';'"></span>
                                        <span class="text-gray-700 font-medium" x-text="cat.category_name"></span>
                                        <input type="checkbox" name="categories[]" :value="cat.category_id" class="ml-auto accent-blue-500 w-5 h-5"
                                            :checked="editTask.categories.includes(cat.category_id)">
                                    </label>
                                </template>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="closeEditModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold">Annuler</button>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>

<hr class="rounded my-8 border-2 border-green-200">

<!-- Tâches réalisées -->
<div x-data="{ open: false }" class="text-center mb-10">
    <button @click="open = !open" class="w-full md:w-1/2 lg:w-1/3 mx-auto bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 shadow-lg transition">
        <span class="emoji">✅</span> Tâches réalisées <span class="badge badge-termine">{{ count($taskFinish) }}</span>
    </button>
    <div x-show="open" @click.away="open = false" class="flex flex-wrap justify-center gap-6 mt-4">
        @forelse ($taskFinish as $taskF)
            <div class="bg-green-50 rounded-2xl shadow p-6 flex flex-col gap-3 hover:scale-105 hover:shadow-2xl transition-all duration-200 animate-fade-in relative w-full md:w-80">
                <div class="flex items-center justify-between mb-2">
                    <a href="{{ route('view_task', $taskF->id) }}" class="font-bold text-lg text-green-700 hover:underline transition cursor-pointer flex-1 text-left line-through">{{ $taskF->task_name }}</a>
                    @php
                        $prio = \App\Models\task_priorities::where('task_id', $taskF->id)->first();
                    @endphp
                    @if($prio)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-200 to-blue-200 text-green-700 animate-pulse shadow">{{ $prio->priority }}</span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2 mt-1">
                    @foreach($taskF->categories as $cat)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#3b82f6' }}; color: white;">{{ $cat->category_name }}</span>
                    @endforeach
                </div>
                @if($taskF->due_date)
                    <div class="mt-2 text-sm text-pink-400 font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::parse($taskF->due_date)->format('d/m/Y') }}
                    </div>
                @endif
                <div class="flex justify-between items-center gap-4 mt-4">
                    <form action="{{ route('uncheck_task_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $taskF->id }}" />
                        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Réactiver">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white-500">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M11.99 7.5 8.24 3.75m0 0L4.49 7.5m3.75-3.75v16.499h11.25" />
                            </svg>
                            Réactiver
                        </button>
                    </form>
                    <form action="{{ route('remove_task_from_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $taskF->id }}" />
                        <input type="hidden" name="project_id" value="{{ $projet->id }}" />
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Supprimer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <h4 class="text-xl font-semibold text-gray-700">Il n'y a actuellement pas de tâche réalisée <span class="emoji">✅</span></h4>
        @endforelse
    </div>
</div>

<script>
    function enableDate() {
        let dt_input = document.getElementById('dt_input');
        dt_input.disabled = !dt_input.disabled;
    }
    // Notification animée
    function showNotification(message, type = 'success') {
        const notif = document.getElementById('notification');
        const notifText = document.getElementById('notif-text');
        const notifEmoji = document.getElementById('notif-emoji');
        notifText.textContent = message;
        notifEmoji.textContent = type === 'success' ? '✅' : '❌';
        notif.classList.remove('opacity-0');
        notif.classList.add('opacity-100');
        setTimeout(() => {
            notif.classList.remove('opacity-100');
            notif.classList.add('opacity-0');
        }, 3000);
    }
    function taskEditModal() {
        return {
            editModalOpen: false,
            editTask: {
                id: null,
                name: '',
                due_date: '',
                has_due: false,
                priority: 'None',
                categories: []
            },
            categorySearch: '',
            allCategories: [
                @foreach($categories as $cat)
                {
                    category_id: {{ $cat->category_id }},
                    category_name: @js($cat->category_name),
                    color: @js($cat->color)
                },
                @endforeach
            ],
            get editFormAction() {
                return '{{ route('update_task_quick') }}';
            },
            openEditModal(task) {
                this.editTask.id = task.id;
                this.editTask.name = task.name;
                this.editTask.due_date = task.due_date ?? '';
                this.editTask.has_due = !!task.due_date;
                this.editTask.priority = task.priority ?? 'None';
                this.editTask.categories = Array.isArray(task.categories) ? [...task.categories] : [];
                this.editModalOpen = true;
                document.body.classList.add('overflow-hidden');
                this.categorySearch = '';
            },
            closeEditModal() {
                this.editModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            },
            filteredCategories() {
                if (!this.categorySearch) return this.allCategories;
                const search = this.categorySearch.toLowerCase();
                return this.allCategories.filter(cat => cat.category_name.toLowerCase().includes(search));
            }
        }
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
