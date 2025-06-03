@include("includes.header")

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>{{ $parent_content["id"] == "Racine" ? 'Racine des dossiers' : 'Dossier ' . $folder->name }} - Zeus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

<!-- Notification -->
<div id="notification" class="fixed top-24 right-6 bg-green-500 text-white font-bold py-2 px-4 rounded transition-opacity duration-300 opacity-0 z-50">
    <div class="progress h-1 bg-white rounded-full"></div>
</div>

<!-- Erreurs -->
@if ($errors->any())
    <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 mb-6 mx-4 rounded animate-pop">
        <h2 class="font-bold mb-2">Il y a eu des erreurs</h2>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Arborescence -->
<div class="mx-auto max-w-5xl p-4 animate-pop">
    <div class="flex items-center justify-center space-x-2 mb-4">
        @php
            $folder_tree = \App\Http\Controllers\FolderController::generateFolderTree($folder->id);
        @endphp
        @foreach ($folder_tree as $index => $folder_arbo)
            <a href="{{ route("folder_view", $folder_arbo["id"]) }}" class="text-blue-600 hover:underline font-bold flex items-center text-lg">
                @if($index == 0) 🏠 @else 📁 @endif
                <span class="ml-1">{{ $folder_arbo["name"] }}</span>
            </a>
            @if($index < count($folder_tree) - 1)
                <span class="text-gray-400 text-xl">➔</span>
            @endif
        @endforeach
    </div>
    <h2 class="text-4xl font-extrabold text-center mb-8 bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 text-transparent bg-clip-text drop-shadow-lg">Arborescence - {{$folder->name}}</h2>
</div>

<!-- Section Ajout -->
<div x-data="addFolderNoteForm()" class="flex flex-col md:flex-row justify-center gap-6 mb-10 animate-pop">
    <!-- Ajout Note -->
    <div class="w-full max-w-xl">
        <button @click="openNote = !openNote" class="bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl mb-4 w-full text-center shadow-lg transition-all duration-300 animate-fade-in">
            <span class="inline-flex items-center"><svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Ajouter une note</span>
        </button>
        <div x-show="openNote" @click.away="openNote = false" class="p-6 bg-white border border-gray-200 rounded-2xl shadow-2xl mt-2 animate-fade-in transition-all duration-300" x-transition:enter="scale-100 opacity-100">
            <h2 class="font-bold text-2xl mb-4 text-center text-blue-700">Nouvelle note</h2>
            <form action="{{ route('add_note') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"/></svg>
                        Nom de la note :
                    </label>
                    <input minlength="1" maxlength="250" type="text" name="add-note" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
                    <input type="hidden" name="path_current" value="{{ $folder_path }}" />
                </div>
                <div>
                    <label class="font-bold flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                        Catégories :
                    </label>
                    <input type="text" placeholder="Rechercher une catégorie..." x-model="categorySearchNote" class="mb-2 w-full px-3 py-1 rounded border border-blue-200 focus:ring-2 focus:ring-blue-400 text-sm bg-blue-50 placeholder-blue-300" />
                    <div class="max-h-40 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col gap-2">
                        <template x-for="cat in filteredCategoriesNote()" :key="cat.category_id">
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
                    Créer la note
                </button>
            </form>
        </div>
    </div>
    <!-- Ajout Dossier -->
    <div class="w-full max-w-xl">
        <button @click="openFolder = !openFolder" class="bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500 text-white font-bold py-3 px-6 rounded-xl mb-4 w-full text-center shadow-lg transition-all duration-300 animate-fade-in">
            <span class="inline-flex items-center"><svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Ajouter un dossier</span>
        </button>
        <div x-show="openFolder" @click.away="openFolder = false" class="p-6 bg-white border border-gray-200 rounded-2xl shadow-2xl mt-2 animate-fade-in transition-all duration-300" x-transition:enter="scale-100 opacity-100">
            <h2 class="font-bold text-2xl mb-4 text-center text-green-700">Nouveau dossier</h2>
            <form action="{{ route('add_folder') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"/></svg>
                        Nom du dossier :
                    </label>
                    <input minlength="1" maxlength="250" type="text" name="add-dossier" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-green-400 w-full shadow-sm" />
                    <input type="hidden" name="path_current" value="{{ $folder_path }}" />
                </div>
                <div>
                    <label class="font-bold flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                        Catégories :
                    </label>
                    <input type="text" placeholder="Rechercher une catégorie..." x-model="categorySearchFolder" class="mb-2 w-full px-3 py-1 rounded border border-green-200 focus:ring-2 focus:ring-green-400 text-sm bg-green-50 placeholder-green-300" />
                    <div class="max-h-40 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col gap-2">
                        <template x-for="cat in filteredCategoriesFolder()" :key="cat.category_id">
                            <label class="flex items-center gap-3 cursor-pointer hover:bg-green-50 rounded px-2 py-1 transition">
                                <span class="w-4 h-4 rounded-full inline-block" :style="'background: ' + (cat.color ?? '#22c55e') + ';'"></span>
                                <span class="text-gray-700 font-medium" x-text="cat.category_name"></span>
                                <input type="checkbox" name="categories[]" :value="cat.category_id" class="ml-auto accent-green-500 w-5 h-5" />
                            </label>
                        </template>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Astuce : cochez une ou plusieurs catégories</p>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-yellow-400 hover:from-green-600 hover:to-yellow-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    Créer le dossier
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Liste des Dossiers et Notes -->
<div x-data="editModalFolderNote({
    allCategories: [
        @foreach($ownedCategories as $catId => $catName)
        {
            category_id: {{ $catId }},
            category_name: @js($catName),
            color: @js(\App\Models\Categorie::find($catId)?->color ?? '#3b82f6')
        },
        @endforeach
        @foreach($notOwnedCategories as $catId => $catName)
        {
            category_id: {{ $catId }},
            category_name: @js($catName),
            color: @js(\App\Models\Categorie::find($catId)?->color ?? '#3b82f6')
        },
        @endforeach
    ]
})" class="flex flex-wrap justify-center gap-6 mb-10 animate-pop">
    @forelse ($folderContents as $item)
        @if (is_array($item) && isset($item['type']))
            @if ($item['type'] === 'folder')
                <div class="bg-gradient-to-br from-green-100 via-blue-50 to-yellow-50 border-2 border-green-300 rounded-2xl shadow-xl p-6 w-80 flex flex-col items-start gap-4 hover:scale-105 transition-transform duration-200 relative">
                    <a href="{{ route('folder_view', $item['id']) }}" class="flex items-center text-2xl font-bold text-green-700 hover:underline">
                        📁 <span class="ml-2">{{ $item['name'] }}</span>
                    </a>
                    <button @click="openEdit('folder', {{ $item['id'] }}, @js($item['name']), @js(array_keys($item['categories'])) )" class="absolute top-3 right-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full p-2 shadow transition" title="Éditer le dossier">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    </button>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($item['categories'] as $category => $id)
                            @php $cat = \App\Models\Categorie::find($category); @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#3b82f6' }}; color: white;">{{ $cat->category_name }}</span>
                        @endforeach
                    </div>
                    <div class="flex gap-2 mt-4 w-full">
                        <form action="{{ route('downloadFolder', ['id' => $item['id']]) }}" method="post" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">⬇️ Télécharger</button>
                        </form>
                        <form action="{{ route('delete_folder') }}" method="post" class="flex-1">
                            @csrf
                            <input name="id" type="hidden" value="{{ $item['id'] }}" />
                            <button class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">❌ Supprimer</button>
                        </form>
                    </div>
                </div>
            @elseif ($item['type'] === 'note')
                <div class="bg-gradient-to-br from-pink-100 via-yellow-50 to-blue-50 border-2 border-pink-300 rounded-2xl shadow-xl p-6 w-80 flex flex-col items-start gap-4 hover:scale-105 transition-transform duration-200 relative">
                    <a href="{{ route('note_view', $item['id']) }}" class="flex items-center text-2xl font-bold text-pink-700 hover:underline">
                        📝 <span class="ml-2">{{ $item['name'] }}</span>
                    </a>
                    <button @click="openEdit('note', {{ $item['id'] }}, @js($item['name']), @js(array_map(function($cat){return $cat['categorie_id'];}, $item['categories']->toArray())) )" class="absolute top-3 right-3 bg-pink-100 hover:bg-pink-200 text-pink-700 rounded-full p-2 shadow transition" title="Éditer la note">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    </button>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($item['categories'] as $category)
                            @php $cat = \App\Models\Categorie::find($category['categorie_id']); @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#ec4899' }}; color: white;">{{ $cat->category_name }}</span>
                        @endforeach
                    </div>
                    <div class="flex gap-2 mt-4 w-full">
                        <form action="{{ route('downloadNote', ['id' => $item['id']]) }}" method="post" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">⬇️ Télécharger</button>
                        </form>
                        <form action="{{ route('delete_note') }}" method="post" class="flex-1">
                            @csrf
                            <input name="id" type="hidden" value="{{ $item['id'] }}" />
                            <button class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center justify-center gap-2">❌ Supprimer</button>
                        </form>
                    </div>
                </div>
            @endif
        @else
            <p class="text-lg font-bold text-red-600">Erreur : Type d'élément non pris en charge</p>
        @endif
    @empty
        <p class="text-lg font-bold text-gray-600">> Le dossier est vide</p>
    @endforelse

    <!-- Modale d'édition rapide -->
    <template x-if="editModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black bg-opacity-40" @click="closeEditModal()"></div>
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative animate-pop border border-blue-200 z-10">
                <button @click="closeEditModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Éditer <span x-text="editType === 'folder' ? 'le dossier' : 'la note'"></span>
                </h3>
                <form :action="editFormAction" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="ressource_id" :value="editId">
                    <input type="hidden" name="ressource_type" :value="editType">
                    <div>
                        <label class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"/></svg>
                            Nom :
                        </label>
                        <input type="text" name="edit_name" x-model="editName" required class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" />
                    </div>
                    <div>
                        <label class="font-bold flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                            Catégories :
                        </label>
                        <input type="text" placeholder="Rechercher une catégorie..." x-model="editCategorySearch" class="mb-2 w-full px-3 py-1 rounded border border-blue-200 focus:ring-2 focus:ring-blue-400 text-sm bg-blue-50 placeholder-blue-300" />
                        <div class="max-h-40 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col gap-2">
                            <template x-for="cat in filteredEditCategories()" :key="cat.category_id">
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-blue-50 rounded px-2 py-1 transition">
                                    <span class="w-4 h-4 rounded-full inline-block" :style="'background: ' + (cat.color ?? '#3b82f6') + ';'"></span>
                                    <span class="text-gray-700 font-medium" x-text="cat.category_name"></span>
                                    <input type="checkbox" name="edit_categories[]" :value="cat.category_id" class="ml-auto accent-blue-500 w-5 h-5" :checked="editCategories.includes(cat.category_id)">
                                </label>
                            </template>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Astuce : cochez une ou plusieurs catégories</p>
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

<!-- Catégories animées et stylisées -->
<div class="mx-auto max-w-5xl p-4 bg-white/90 rounded-2xl shadow-xl mb-10 animate-pop">
    <h2 class="font-bold mb-4 text-blue-600 text-xl flex items-center gap-2">🎨 Liste des catégories</h2>
    <div class="flex flex-wrap gap-3">
        @foreach ($ressourceCategories as $category)
            @php $cat = \App\Models\Categorie::find($category->categorie_id); @endphp
            <div class="flex items-center px-4 py-2 rounded-full shadow-md font-semibold text-white"
                 style="background: linear-gradient(90deg, {{ $cat->color }}, #fff2 80%); box-shadow: 0 2px 8px {{ $cat->color }}44;">
                <svg class="w-5 h-5 mr-2 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                </svg>
                {{ $cat->category_name }}
            </div>
        @endforeach
    </div>
</div>

<!-- Gestion des Catégories -->
<div class="mx-auto max-w-5xl mb-10 animate-pop">
    <div class="bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col gap-6">
        <h2 class="font-bold text-lg mb-2 text-blue-600 flex items-center gap-2">⚙️ Gestion des catégories</h2>
        <form method="post" action="{{ route("addCategory") }}" class="flex flex-col md:flex-row gap-4 items-center">
            @csrf
            <select name="category" class="border-2 border-blue-200 rounded-lg py-2 px-4 w-full md:w-1/2 focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                @foreach ($notOwnedCategories as $categoryId => $categoryName)
                    <option value="{{ $categoryId }}">{{ $categoryName }}</option>
                @endforeach
            </select>
            <input name="ressourceId" value="{{ $folder->id }}" type="hidden" />
            <input name="ressourceType" value="folder" type="hidden" />
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-pink-500 hover:from-pink-500 hover:to-yellow-400 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">➕ Ajouter</button>
        </form>
        <form method="post" action="{{ route("removeCategory") }}" class="flex flex-col md:flex-row gap-4 items-center">
            @csrf
            <select name="removeCategory" class="border-2 border-pink-200 rounded-lg py-2 px-4 w-full md:w-1/2 focus:ring-2 focus:ring-pink-400 focus:outline-none transition">
                @foreach ($ressourceCategories as $categoryId => $category)
                    <option value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-yellow-400 hover:to-blue-500 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">➖ Supprimer</button>
        </form>
    </div>
</div>

<!-- Partage Utilisateur -->
@if ($folder->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <div class="mx-auto max-w-5xl mb-10 animate-pop">
        <div class="bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col gap-6">
            <h2 class="font-bold text-lg mb-2 text-pink-600 flex items-center gap-2">🤝 Partage utilisateur</h2>
            <form action="{{ route("add_folder_share") }}" method="post" class="flex flex-col md:flex-row gap-4 items-center">
                @csrf
                <input name="id_share" type="number" min="0" placeholder="ID utilisateur..." class="border-2 border-pink-200 rounded-lg py-2 px-4 w-full md:w-1/3 focus:ring-2 focus:ring-pink-400 focus:outline-none transition" />
                <select name="right" class="border-2 border-yellow-200 rounded-lg py-2 px-4 w-full md:w-1/3 focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                    <option value="RO">Lecture Seule (Read Only)</option>
                    <option value="RW">Lecture et Écriture</option>
                    <option value="F">Tout (Lecture, Écriture, Suppression, Renommer)</option>
                </select>
                <input type="hidden" name="folder_id" value="{{ $folder->id }}" />
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-yellow-400 hover:to-blue-500 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">🤝 Partager</button>
            </form>
        </div>
    </div>
    <!-- Liste des autorisations utilisateurs -->
    <div class="mx-auto max-w-5xl p-4 bg-white/90 rounded-2xl shadow-xl mb-10 animate-pop">
        <h3 class="font-bold text-lg mb-4 text-yellow-600 flex items-center gap-2">🔑 Liste des autorisations utilisateurs</h3>
        <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead>
            <tr class="bg-gradient-to-r from-blue-100 to-pink-100">
                <th class="p-2 border border-gray-300">👤 Nom d'utilisateur</th>
                <th class="p-2 border border-gray-300">🆔 ID</th>
                <th class="p-2 border border-gray-300">🔒 Droit</th>
                <th class="p-2 border border-gray-300">🛠️ Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($usersPermissionList as $perm)
                <tr>
                    <td class="p-2 border border-gray-300">{{ \App\Models\User::find($perm->dest_id)->name }}</td>
                    <td class="p-2 border border-gray-300">{{ $perm->dest_id }}</td>
                    <td class="p-2 border border-gray-300">{{ $perm->perm }}</td>
                    <td class="p-2 border border-gray-300">
                        <form action="{{ route("delete_perm", ["id" => $perm->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow flex items-center gap-2">❌ Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<script src="{{ asset('js/notification.js') }}"></script>
<script>
function addFolderNoteForm() {
    return {
        openNote: false,
        openFolder: false,
        categorySearchNote: '',
        categorySearchFolder: '',
        allCategories: [
            @foreach($notOwnedCategories as $catId => $catName)
            {
                category_id: {{ $catId }},
                category_name: @js($catName),
                color: @js(\App\Models\Categorie::find($catId)?->color ?? '#3b82f6')
            },
            @endforeach
        ],
        filteredCategoriesNote() {
            if (!this.categorySearchNote) return this.allCategories;
            const search = this.categorySearchNote.toLowerCase();
            return this.allCategories.filter(cat => cat.category_name.toLowerCase().includes(search));
        },
        filteredCategoriesFolder() {
            if (!this.categorySearchFolder) return this.allCategories;
            const search = this.categorySearchFolder.toLowerCase();
            return this.allCategories.filter(cat => cat.category_name.toLowerCase().includes(search));
        }
    }
}

function editModalFolderNote({allCategories}) {
    return {
        editModalOpen: false,
        editType: '',
        editId: null,
        editName: '',
        editCategories: [],
        editCategorySearch: '',
        openEdit(type, id, name, categories) {
            this.editType = type;
            this.editId = id;
            this.editName = name;
            this.editCategories = [...categories];
            this.editModalOpen = true;
            this.editCategorySearch = '';
        },
        closeEditModal() {
            this.editModalOpen = false;
        },
        filteredEditCategories() {
            if (!this.editCategorySearch) return allCategories;
            const search = this.editCategorySearch.toLowerCase();
            return allCategories.filter(cat => cat.category_name.toLowerCase().includes(search));
        },
        get editFormAction() {
            return '/folder-note-quick-update'; // À adapter côté backend
        }
    }
}
</script>
</body>
</html>

@include("includes.footer")
