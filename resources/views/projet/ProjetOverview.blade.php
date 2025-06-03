@include("includes.header")

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des projets</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/notification/notification.css') }}" />
    <style>
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: pop 0.5s cubic-bezier(.4,0,.2,1) both; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-pink-50 to-yellow-50 min-h-screen font-sans text-gray-900">

<div id="notification" class="fixed top-24 right-6 bg-green-500 text-white font-bold py-2 px-4 rounded transition-opacity duration-300 opacity-0 z-50">
    <div class="progress h-1 bg-white rounded-full"></div>
</div>

<div class="text-center my-8 animate-pop">
    <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-blue-500 via-pink-500 to-yellow-400 bg-clip-text text-transparent drop-shadow-lg">🚀 Mes Projets</h1>
    <p class="text-lg text-gray-500 mt-2">Organisez, collaborez, réalisez vos projets avec style !</p>
</div>

<!-- Formulaire d'ajout de projet -->
<div class="flex justify-center mb-10 animate-pop">
    <div class="w-full max-w-xl bg-white/90 rounded-2xl shadow-xl p-6 border-2 border-blue-200" x-data="addProjectForm()">
        <h2 class="font-bold text-2xl mb-4 text-blue-700 flex items-center gap-2">🆕 Ajouter un projet</h2>
        <form action="{{ route('store_projet') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="projet_name" class="font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"/></svg>
                    Nom du projet :
                </label>
                <input minlength="1" maxlength="250" type="text" id="projet_name" name="projet_name" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
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
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Créer le projet
            </button>
        </form>
    </div>
</div>

<!-- Projets en cours et terminés + modale d'édition dans le même x-data Alpine -->
<div x-data="projectEditModal()">
    <h3 class="font-bold text-2xl mb-6 text-center text-blue-700 animate-pop flex items-center justify-center gap-2">📂 Projets en cours</h3>
    <div class="flex flex-wrap justify-center gap-8 mb-10 animate-pop">
        @foreach ($userProjectUnDone as $projet)
            <div class="bg-gradient-to-br from-blue-100 via-pink-50 to-yellow-50 border-2 border-blue-300 rounded-2xl shadow-xl p-6 w-96 flex flex-col items-start gap-4 hover:scale-105 transition-transform duration-200 relative">
                <div class="flex items-center w-full">
                    <a href="{{ route('projet_view', $projet->id) }}" class="flex items-center text-2xl font-bold text-blue-700 hover:underline">
                        📁 <span class="ml-2">{{ $projet->name }}</span>
                    </a>
                    <button @click="openEditModal({id: {{ $projet->id }}, name: @js($projet->name), categories: @js(array_keys($projet->categories))})" class="ml-2 p-2 rounded-full hover:bg-blue-100 transition" title="Éditer le projet">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($projet->categories as $category => $id)
                        @php $cat = \App\Models\Categorie::find($category); @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#3b82f6' }}; color: white;">{{ $cat->category_name }}</span>
                    @endforeach
                </div>
                <div class="flex gap-2 mt-4 w-full">
                    <form action="{{ route('archive_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">✅ Terminer</button>
                    </form>
                    <form action="{{ route('delete_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">❌ Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <h3 class="font-bold text-2xl mb-6 text-center text-green-700 animate-pop flex items-center justify-center gap-2">🏁 Projets terminés</h3>
    <div class="flex flex-wrap justify-center gap-8 mb-10 animate-pop">
        @foreach ($userProjetsDone as $projet)
            <div class="bg-gradient-to-br from-green-100 via-blue-50 to-yellow-50 border-2 border-green-300 rounded-2xl shadow-xl p-6 w-96 flex flex-col items-start gap-4 hover:scale-105 transition-transform duration-200 relative">
                <div class="flex items-center w-full">
                    <a href="{{ route('projet_view', $projet->id) }}" class="flex items-center text-2xl font-bold text-green-700 hover:underline">
                        🏁 <span class="ml-2">{{ $projet->name }}</span>
                    </a>
                    <button @click="openEditModal({id: {{ $projet->id }}, name: @js($projet->name), categories: @js(array_keys($projet->categories))})" class="ml-2 p-2 rounded-full hover:bg-green-100 transition" title="Éditer le projet">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($projet->categories as $category => $id)
                        @php $cat = \App\Models\Categorie::find($category); @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#3b82f6' }}; color: white;">{{ $cat->category_name }}</span>
                    @endforeach
                </div>
                <div class="flex gap-2 mt-4 w-full">
                    <form action="{{ route('archive_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">🔄 Reprendre</button>
                    </form>
                    <form action="{{ route('delete_project') }}" method="POST" class="flex-1">
                        @csrf
                        <input name="project_id" type="hidden" value="{{ $projet->id }}" />
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">❌ Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modale d'édition projet -->
    <template x-if="editModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-40" @click="closeEditModal()"></div>
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative animate-pop border border-blue-200 z-10"
                 x-effect="if(editModalOpen){ document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }">
                <button @click="closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Éditer le projet
                </h3>
                <form action="{{ route('update_project') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="projet_id" :value="editProject.id">
                    <div>
                        <label class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"/></svg>
                            Nom du projet :
                        </label>
                        <input type="text" name="projet_name" x-model="editProject.name" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
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
                                        :checked="editProject.categories.includes(cat.category_id)">
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

<script src="{{ asset('js/notification.js') }}"></script>
<script>
    @if (session('success'))
    showNotification("{{ session('success') }}", 'success');
    @elseif (session('failure'))
    showNotification("{{ session('failure') }}", 'failure');
    @endif
</script>

<script>
function addProjectForm() {
    return {
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

function projectEditModal() {
    return {
        editModalOpen: false,
        editProject: {
            id: null,
            name: '',
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
        openEditModal(project) {
            this.editProject.id = project.id;
            this.editProject.name = project.name;
            this.editProject.categories = Array.isArray(project.categories) ? [...project.categories] : [];
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

@include("includes.footer")
    