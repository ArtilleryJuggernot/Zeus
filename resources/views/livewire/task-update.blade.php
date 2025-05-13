<div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col gap-3 hover:scale-105 hover:shadow-2xl transition-all duration-200 animate-fade-in relative" x-data="{ isEditing: false, showConfirm: false }">
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('view_task', $taskId) }}" class="font-bold text-xl text-center text-blue-600 hover:underline transition cursor-pointer flex-1 text-left">{{ $taskName }}</a>
        @php
            $prio = isset($priority) && $priority !== 'None' ? $priority : (\App\Models\task_priorities::where('task_id', $taskId)->first()->priority ?? null);
        @endphp
        @if($prio && $prio !== 'None')
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-pink-200 to-yellow-200 text-pink-700 animate-pulse shadow">{{ $prio }}</span>
        @endif
        <!-- Bouton éditer -->
        {{-- BOUTON ÉDITER SUPPRIMÉ --}}
    </div>
    <div class="flex flex-wrap gap-2 mt-1">
        @php
            $categories = \App\Models\Task::find($taskId)?->categories ?? collect();
        @endphp
        @foreach($categories as $cat)
            @php $catObj = \App\Models\Categorie::find($cat->categorie_id); @endphp
            @if($catObj)
                <span class="px-2 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $catObj->color ?? '#3b82f6' }}; color: white;">{{ $catObj->category_name }}</span>
            @endif
        @endforeach
    </div>
    @if($dueDate)
        <div class="mt-2 text-sm text-pink-600 font-semibold flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ \Carbon\Carbon::parse($dueDate)->format('d/m/Y') }}
        </div>
    @endif
    <div class="flex justify-between items-center gap-4 mt-4">
        <button wire:click="updateTaskStatus({{ $taskId }}, {{ $is_finish ? '0' : '1' }})" class="w-full bg-green-500 hover:bg-green-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="{{ $is_finish ? 'Rendre active' : 'Valider' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            {{ $is_finish ? 'Réactiver' : 'Valider' }}
        </button>
        <button @click="showConfirm = true" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow transition flex items-center justify-center gap-2 font-bold min-w-[48px]" title="Supprimer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            Supprimer
        </button>
    </div>
    <!-- Modale de confirmation de suppression -->
    <div x-show="showConfirm" class="fixed inset-0 z-100 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md relative animate-pop border border-red-200 z-100 flex flex-col items-center">
            <button @click="showConfirm = false" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-red-700 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                Confirmation de suppression
            </h3>
            <p class="mb-6 text-gray-700 text-center">Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est <span class="font-bold text-red-600">irréversible</span>.</p>
            <div class="w-full flex flex-col items-center gap-4">
                <button wire:click="deleteTask({{ $taskId }})" @click="showConfirm = false" class="bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition flex items-center gap-2 text-lg w-full justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    Oui, supprimer définitivement
                </button>
                <button @click="showConfirm = false" class="mt-2 px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold">Annuler</button>
            </div>
        </div>
    </div>
    <!-- Modale d'édition -->
    <div x-show="isEditing" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-60" x-init="$watch('isEditing', value => { document.body.style.overflow = value ? 'hidden' : '' })" style="backdrop-filter: blur(2px);">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative animate-pop border border-blue-200">
            <button @click="$wire.closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                Éditer la tâche
            </h3>
            <form wire:submit.prevent="updateTask" class="space-y-5">
                <div>
                    <label class="font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                        </svg>
                        Nom de la tâche :
                    </label>
                    <input type="text" wire:model="taskName" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
                    @error('taskName') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center gap-3">
                    <label class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Date limite ?
                    </label>
                    <input type="date" wire:model="dueDate" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition w-1/2" />
                </div>
                <div>
                    <label class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        Priorité :
                    </label>
                    <select wire:model="priority" class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-purple-400">
                        <option value="None">Aucune</option>
                        <option value="Urgence">🔥 Urgence</option>
                        <option value="Grande priorité">⚡ Grande priorité</option>
                        <option value="Prioritaire">⭐ Prioritaire</option>
                    </select>
                    @error('priority') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <!-- Catégories -->
                <div>
                    <label class="font-bold flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        Catégories :
                    </label>
                    <input type="text" placeholder="Rechercher une catégorie..." wire:model="categorySearch" class="mb-2 w-full px-3 py-1 rounded border border-blue-200 focus:ring-2 focus:ring-blue-400 text-sm bg-blue-50 placeholder-blue-300" />
                    <div class="max-h-40 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col gap-2">
                        @foreach($allCategories as $cat)
                            @if(!$categorySearch || str_contains(strtolower($cat['category_name']), strtolower($categorySearch)))
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-blue-50 rounded px-2 py-1 transition">
                                    <span class="w-4 h-4 rounded-full inline-block" style="background: {{ $cat['color'] ?? '#3b82f6' }};"></span>
                                    <span class="text-gray-700 font-medium">{{ $cat['category_name'] }}</span>
                                    <input type="checkbox" wire:model="selectedCategories" value="{{ $cat['category_id'] }}" class="ml-auto accent-blue-500 w-5 h-5" />
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="$wire.closeEditModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold">Annuler</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Ajout des listeners JS pour le scroll du body -->
<script>
    window.addEventListener('lock-scroll', () => {
        document.body.style.overflow = 'hidden';
    });
    window.addEventListener('unlock-scroll', () => {
        document.body.style.overflow = '';
    });
</script>
