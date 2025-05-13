@include("includes.header")

<div class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 min-h-screen py-8 px-2">
    <!-- Header & Stats -->
    <div class="text-center my-8 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 bg-clip-text text-transparent animate-gradient drop-shadow-lg">
            Mes Tâches
        </h1>
        <p class="text-lg text-gray-500 mt-2">Organisez, priorisez, accomplissez avec style ✨</p>
        <div class="flex justify-center gap-4 mt-4">
            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold shadow">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                {{ $nb_unfinished }} en cours
            </span>
            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full font-semibold animate-pulse shadow">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2l4-4"/></svg>
                {{ $nb_finished }} terminées
            </span>
        </div>
    </div>

    <!-- Erreurs -->
    @if ($errors->any())
        <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 mb-6 mx-auto rounded max-w-xl animate-fade-in">
            <h2 class="font-bold mb-2">Il y a eu des erreurs</h2>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire d'ajout de tâche -->
    <div x-data="addTaskForm()" class="mb-10 flex justify-center">
        <div class="w-full max-w-xl">
            <button @click="open = !open" class="bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl mb-4 w-full text-center shadow-lg transition-all duration-300 animate-fade-in">
                <span class="inline-flex items-center"><svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Ajouter une tâche</span>
            </button>
            <div x-show="open" @click.away="open = false" class="p-6 bg-white border border-gray-200 rounded-2xl shadow-2xl mt-2 animate-fade-in transition-all duration-300" x-transition:enter="scale-100 opacity-100">
                <h2 class="font-bold text-2xl mb-4 text-center text-blue-700">Nouvelle tâche</h2>
                <form action="{{ route('store_task') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="tache_name" class="font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
  <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
</svg>

                            Nom de la tâche :
                        </label>
                        <input minlength="1" maxlength="250" type="text" id="tache_name" name="tache_name" required
                               class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
                    </div>

                    <div class="flex items-center gap-3">
                        <label for="is_due" class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Date limite ?
                        </label>
                        <input id="is_due" type="checkbox" name="is_due" class="accent-pink-500 w-5 h-5" @change="document.getElementById('dt_input').disabled = !document.getElementById('is_due').checked" />
                        <input required disabled id="dt_input" type="date" name="dt_input"
                               class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition w-1/2" />
                    </div>

                    <div>
                        <label for="priority" class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            Priorité :
                        </label>
                        <select id="priority" name="priority" class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-purple-400">
                            <option value="None">Aucune</option>
                            <option value="Urgence" class="text-red-600 font-bold">🔥 Urgence</option>
                            <option value="Grande priorité" class="text-orange-600 font-bold">⚡ Grande priorité</option>
                            <option value="Prioritaire" class="text-yellow-600 font-bold">⭐ Prioritaire</option>
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
                                    <input type="checkbox" name="categories[]" :value="cat.category_id" class="ml-auto accent-blue-500 w-5 h-5" />
                                </label>
                            </template>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Astuce : cochez une ou plusieurs catégories</p>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        Créer la tâche
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des tâches en cours -->
    <div class="max-w-7xl mx-auto" x-data="taskEditModal()">
        <h2 class="font-bold text-2xl mb-6 text-center text-blue-700 animate-fade-in flex items-center justify-center gap-2">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            Tâches en cours <span class="ml-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">{{ $nb_unfinished }}</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">
            @forelse ($task_list_unfinish as $task)
                <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col gap-3 hover:scale-105 hover:shadow-2xl transition-all duration-200 animate-fade-in relative">
                    <div class="flex items-center justify-between mb-2">
                        <a href="{{ route('view_task', $task->id) }}" class="font-bold text-xl text-center text-blue-600 hover:underline transition cursor-pointer flex-1 text-left">{{ $task->task_name }}</a>
                        @php
                            $prio = \App\Models\task_priorities::where('task_id', $task->id)->first();
                        @endphp
                        @if($prio)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-pink-200 to-yellow-200 text-pink-700 animate-pulse shadow">{{ $prio->priority }}</span>
                        @endif
                        <!-- Bouton éditer -->
                        <button @click="openEditModal({
                            id: {{ $task->id }},
                            name: @js($task->task_name),
                            due_date: @js($task->due_date),
                            priority: @js(optional($prio)->priority ?? 'None'),
                            categories: @js($task->categories->pluck('categorie_id')->toArray())
                        })" class="ml-2 p-2 rounded-full hover:bg-blue-100 transition" title="Éditer la tâche">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach($task->categories as $cat)
                            @php $catObj = \App\Models\Categorie::find($cat->categorie_id); @endphp
                            @if($catObj)
                                <span class="px-2 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $catObj->color ?? '#3b82f6' }}; color: white;">{{ $catObj->category_name }}</span>
                            @endif
                        @endforeach
                    </div>
                    @if($task->due_date)
                        <div class="mt-2 text-sm text-pink-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                        </div>
                    @endif
                    <div class="flex justify-between items-center gap-4 mt-4">
                        <form action="{{ route('update_task_finish') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="task_completed" value="on">
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Marquer comme terminée">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Valider
                            </button>
                        </form>
                        <form action="{{ route('delete_task') }}" method="POST" onsubmit="return confirm('Supprimer cette tâche ?');" class="flex-1">
                            @csrf
                            <input type="hidden" name="id" value="{{ $task->id }}">
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Supprimer">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-400 italic">Aucune tâche en cours</div>
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

    <!-- Liste des tâches terminées -->
    <div x-data="{ open: false }" class="mt-12 max-w-7xl mx-auto animate-fade-in">
        <button @click="open = !open" class="bg-gradient-to-r from-green-400 to-blue-400 hover:from-green-500 hover:to-blue-500 text-white font-bold py-3 px-6 rounded-xl w-full text-center shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2l4-4"/></svg>
            Tâches finies ({{ $nb_finished }})
        </button>
        <div x-show="open" @click.away="open = false" class="p-6 bg-white border border-gray-200 rounded-2xl shadow-2xl mt-2 animate-fade-in transition-all duration-300" x-transition:enter="scale-100 opacity-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-data="$root">
                @forelse ($task_list_finish as $task)
                    <div class="bg-gray-50 rounded-2xl shadow p-6 flex flex-col gap-3 hover:scale-105 hover:shadow-2xl transition-all duration-200 animate-fade-in relative">
                        <div class="flex items-center justify-between mb-2">
                            <a href="{{ route('view_task', $task->id) }}" class="font-bold text-xl text-center text-blue-400 hover:underline transition cursor-pointer flex-1 text-left line-through">{{ $task->task_name }}</a>
                            @php
                                $prio = \App\Models\task_priorities::where('task_id', $task->id)->first();
                            @endphp
                            @if($prio)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-200 to-blue-200 text-green-700 animate-pulse shadow">{{ $prio->priority }}</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($task->categories as $cat)
                                @php $catObj = \App\Models\Categorie::find($cat->categorie_id); @endphp
                                @if($catObj)
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $catObj->color ?? '#3b82f6' }}; color: white;">{{ $catObj->category_name }}</span>
                                @endif
                            @endforeach
                        </div>
                        @if($task->due_date)
                            <div class="mt-2 text-sm text-pink-400 font-semibold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                            </div>
                        @endif
                        <div class="flex justify-between items-center gap-4 mt-4">
                            <!-- Bouton rendre active -->
                            <form action="{{ route('update_task_finish') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <input type="hidden" name="task_completed" value="off">
                                <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Rendre la tâche active">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white-500">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.99 7.5 8.24 3.75m0 0L4.49 7.5m3.75-3.75v16.499h11.25" />
                                    </svg>
                                    Réactiver
                                </button>
                            </form>
                            <form action="{{ route('delete_task') }}" method="POST" onsubmit="return confirm('Supprimer cette tâche ?');" class="flex-1">
                                @csrf
                                <input type="hidden" name="id" value="{{ $task->id }}">
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Supprimer">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-300 italic">Aucune tâche terminée</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/notification.js') }}"></script>
<script>
    @if(session("success"))
    showNotification("{{ session('success') }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session('failure') }}", 'failure');
    @endif
</script>
<script>
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

<script>
function addTaskForm() {
    return {
        open: false,
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
        filteredCategories() {
            if (!this.categorySearch) return this.allCategories;
            const search = this.categorySearch.toLowerCase();
            return this.allCategories.filter(cat => cat.category_name.toLowerCase().includes(search));
        }
    }
}
</script>

@include("includes.footer")
