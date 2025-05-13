<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Zeus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/notification/notification.css') }}">
    <script src="{{ asset('js/notification.js') }}"></script>
    <style>
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: pop 0.5s cubic-bezier(.4,0,.2,1) both; }
    </style>
</head>
@include('includes.header')
<body class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 min-h-screen flex flex-col">
<div class="flex-1 w-full px-0 py-8 max-w-full mx-auto">
    <!-- Header & Stats -->
    <div class="text-center my-8 animate-fade-in">
        <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 bg-clip-text text-transparent animate-gradient drop-shadow-lg flex items-center justify-center gap-2">
            Bienvenue, {{ Auth::user()->name }}
        </h1>
        <p class="text-lg text-gray-500 mt-2">Votre tableau de bord productif et stylé ✨</p>
        <div class="flex justify-center gap-4 mt-4 flex-wrap">
            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold shadow animate-pop">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                {{ count($habitudes) }} habitudes
            </span>
            <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full font-semibold shadow animate-pop">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                {{ count($task_priority) }} tâches prioritaires
            </span>
            <span class="inline-flex items-center px-3 py-1 bg-pink-100 text-pink-700 rounded-full font-semibold shadow animate-pop">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ count($tachesTimed) }} tâches à échéance
            </span>
            <span class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-700 rounded-full font-semibold shadow animate-pop">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                {{ count($tachePasse) }} tâches non réalisées
            </span>
        </div>
    </div>

    <!-- Accordéons verticaux pour chaque section -->
    <div class="w-full max-w-[1800px] mx-auto flex flex-col gap-8 animate-fade-in">
        <!-- Habitudes -->
        <div x-data="{open:true}">
            <button @click="open=!open" class="w-full flex items-center justify-between px-6 py-4 bg-blue-200 hover:bg-blue-300 rounded-xl font-bold text-blue-700 shadow transition-all text-lg">
                <span class="flex items-center gap-2"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>Habitudes à faire 🏆</span>
                <svg :class="{'rotate-180':open}" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($habitudes as $task)
                    <livewire:task-update :taskId="$task->id" :taskName="$task->task_name" :dueDate="$task->due_date" :is_finish="$task->is_finish" :allCategories="$allCategories" />
                @empty
                    <div class="text-center text-gray-400 italic">Aucune habitude à faire</div>
                @endforelse
            </div>
        </div>

        <!-- Tâches prioritaires -->
        <div x-data="{open:true}">
            <button @click="open=!open" class="w-full flex items-center justify-between px-6 py-4 bg-yellow-200 hover:bg-yellow-300 rounded-xl font-bold text-yellow-700 shadow transition-all text-lg">
                <span class="flex items-center gap-2"><svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>Tâches prioritaires</span>
                <svg :class="{'rotate-180':open}" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($task_priority as $task)
                    <livewire:task-update :taskId="$task->task_id" :taskName="$task->task_name" :dueDate="$task->due_date" :is_finish="$task->is_finish" :priority="$task->priority" :allCategories="$allCategories" />
                @empty
                    <div class="text-center text-gray-400 italic">Aucune tâche prioritaire</div>
                @endforelse
            </div>
        </div>
        <!-- Tâches avec date limite -->
        <div x-data="{open:true}">
            <button @click="open=!open" class="w-full flex items-center justify-between px-6 py-4 bg-pink-200 hover:bg-pink-300 rounded-xl font-bold text-pink-700 shadow transition-all text-lg">
                <span class="flex items-center gap-2"><svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Tâches avec date limite</span>
                <svg :class="{'rotate-180':open}" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($tachesTimed as $task)
                    <livewire:task-update :taskId="$task->id" :taskName="$task->task_name" :dueDate="$task->due_date" :is_finish="$task->is_finish" :allCategories="$allCategories" />
                @empty
                    <div class="text-center text-gray-400 italic">Aucune tâche à échéance</div>
                @endforelse
            </div>
        </div>
        <!-- Tâches non réalisées -->
        <div x-data="{open:true}">
            <button @click="open=!open" class="w-full flex items-center justify-between px-6 py-4 bg-gray-200 hover:bg-gray-300 rounded-xl font-bold text-gray-700 shadow transition-all text-lg">
                <span class="flex items-center gap-2"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>Tâches non réalisées</span>
                <svg :class="{'rotate-180':open}" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($tachePasse as $task)
                    <livewire:task-update :taskId="$task->id" :taskName="$task->task_name" :dueDate="$task->due_date" :is_finish="$task->is_finish" :allCategories="$allCategories" />
                @empty
                    <div class="text-center text-gray-400 italic">Aucune tâche non réalisée</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script>
function homePage() {
    return {
        editModalOpen: false,
        editTaskData: null,
        openEditModal(task) {
            this.editTaskData = task;
            this.editModalOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeEditModal() {
            this.editModalOpen = false;
            this.editTaskData = null;
            document.body.classList.remove('overflow-hidden');
        }
    }
}
// Listener JS pour ouvrir la modale depuis n'importe quelle carte Livewire
window.addEventListener('open-edit-modal', function(e) {
    const scope = document.querySelector('[x-data]');
    if(scope && scope.__x) {
        scope.__x.$data.openEditModal(e.detail);
    }
});
</script>
<script>
    @if(session('success'))
    showNotification("{{ session('success') }}", 'success');
    @elseif(session('failure'))
    showNotification("{{ session('failure') }}", 'failure');
    @endif
</script>
@include('includes.footer')

<!-- Modale globale d'édition (à placer à la fin du body) -->
<template x-if="editModalOpen">
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-60" x-init="$watch('editModalOpen', value => { document.body.style.overflow = value ? 'hidden' : '' })" style="backdrop-filter: blur(2px);">
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg mx-4 animate-pop border border-blue-200">
            <button @click="closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                Éditer la tâche
            </h3>
            <form x-show="editTaskData" :action="'/tasks/update/' + (editTaskData?.taskId ?? '')" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="task_id" :value="editTaskData?.taskId">
                <div>
                    <label class="font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                        </svg>
                        Nom de la tâche :
                    </label>
                    <input type="text" name="tache_name" :value="editTaskData?.taskName" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
                </div>
                <div class="flex items-center gap-3">
                    <label class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Date limite ?
                    </label>
                    <input type="date" name="dt_input" :value="editTaskData?.dueDate" class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition w-1/2" />
                </div>
                <div>
                    <label class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        Priorité :
                    </label>
                    <select name="priority" :value="editTaskData?.priority" class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-purple-400">
                        <option value="None">Aucune</option>
                        <option value="Urgence">🔥 Urgence</option>
                        <option value="Grande priorité">⚡ Grande priorité</option>
                        <option value="Prioritaire">⭐ Prioritaire</option>
                    </select>
                </div>
                <!-- Catégories (à adapter si besoin) -->
                <div>
                    <label class="font-bold flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        Catégories :
                    </label>
                    <!-- À compléter pour la gestion des catégories -->
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
