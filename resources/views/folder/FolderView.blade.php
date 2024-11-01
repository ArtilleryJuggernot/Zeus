@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    @if ($parent_content["id"] == "Racine")
        <title>Racine des dossiers - Zeus</title>
    @else
        <title>Dossier {{ $folder->name }} - Zeus</title>
    @endif
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

<!-- Arborescence -->
<div class="arborescence mx-auto max-w-5xl p-4">
    <h2 class="text-3xl font-bold text-center mb-6">Arborescence - {{$folder->name}}</h2>
    <div class="flex items-center justify-center space-x-2">
        @php
            $folder_tree = \App\Http\Controllers\FolderController::generateFolderTree($folder->id);
        @endphp
        @foreach ($folder_tree as $index => $folder_arbo)
            <a href="{{ route("folder_view", $folder_arbo["id"]) }}" class="text-blue-500 hover:underline font-bold flex items-center">
                @if($index == 0) 🏠 @else 📁 @endif
                <span>{{ $folder_arbo["name"] }}</span>
            </a>
            @if($index < count($folder_tree) - 1)
                <span class="text-gray-400">></span>
            @endif
        @endforeach
    </div>
</div>

<!-- Section Ajout -->
<div class="add-section flex justify-center space-x-4 mb-6">
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Ajouter une note 📝</button>
        <div x-show="open" @click.away="open = false"
             class="absolute left-0 mt-2 w-64 p-4 bg-white border border-gray-300 rounded shadow z-50">
            <form action="{{ route("add_note") }}" method="post">
                <label for="add-note" class="font-bold">Entrez la note que vous souhaitez ajouter :</label>
                <input name="add-note" type="text" class="border border-gray-300 rounded-md w-full p-2 mt-1" />
                <input type="hidden" name="path_current" value="{{ $folder_path }}" />
                <input type="submit" value="Envoyer" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" />
                @csrf
            </form>
        </div>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="bg-green-600 text-white font-bold py-2 px-4 rounded">Ajouter un dossier 📁</button>
        <div x-show="open" @click.away="open = false"
             class="absolute left-0 mt-2 w-64 p-4 bg-white border border-gray-300 rounded shadow z-50">
            <form action="{{ route("add_folder") }}" method="post">
                <label for="add-dossier" class="font-bold">Entrez le nom du dossier que vous souhaitez ajouter :</label>
                <input name="add-dossier" type="text" class="border border-gray-300 rounded-md w-full p-2 mt-1" />
                <input type="hidden" name="path_current" value="{{ $folder_path }}" />
                <input type="submit" value="Envoyer" class="mt-3 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full" />
                @csrf
            </form>
        </div>
    </div>
</div>


<!-- Liste des Dossiers et Notes -->
<div class="folders flex flex-wrap justify-start">
    @forelse ($folderContents as $item)
        <div class="flex-grow  bg-white flex flex-col justify-between">
            @if ($item['type'] == 'folder')
                <livewire:folder-item :folder="$item" :ownedCategories="$ownedCategories" :notOwnedCategories="$notOwnedCategories" :key="$item['id']" />
            @else
                <livewire:note-item :note="$item" :ownedCategories="$ownedCategories" :notOwnedCategories="$notOwnedCategories" :key="$item['id']" />
            @endif
        </div>
    @empty
        <p class="text-lg font-bold text-gray-600">> Le dossier est vide</p>
    @endforelse
</div>



<!-- Catégories -->
<div class="cat_display mx-auto max-w-5xl p-4 bg-white rounded-lg shadow-md mb-6">
    <h2 class="font-bold mb-4">Liste des catégories</h2>
    <div class="flex flex-wrap gap-2">
        @foreach ($ressourceCategories as $category)
            @php
                $category = \App\Models\Categorie::find($category->categorie_id);
            @endphp
            <div class="category text-white font-bold py-1 px-3 rounded" style="background-color: {{ $category->color }}">
                {{ $category->category_name }}
            </div>
        @endforeach
    </div>
</div>

<!-- Gestion des Catégories -->
<div class="mx-auto max-w-5xl mb-6" x-data="{ open: false }">
    <button @click="open = !open" class="w-full text-left text-gray-900 font-bold py-2 px-4 bg-gray-200 rounded">Gestion des catégories</button>
    <div x-show="open" @click.away="open = false" class="p-4 bg-white border border-gray-300 rounded shadow mt-2">
        <form method="post" action="{{ route("addCategory") }}" class="mb-4">
            @csrf
            <label for="category" class="font-bold">Ajouter une catégorie :</label>
            <select name="category" id="category" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                @foreach ($notOwnedCategories as $categoryId => $categoryName)
                    <option value="{{ $categoryId }}">{{ $categoryName }}</option>
                @endforeach
            </select>
            <input name="ressourceId" value="{{ $folder->id }}" type="hidden" />
            <input name="ressourceType" value="folder" type="hidden" />
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Ajouter</button>
        </form>

        <form method="post" action="{{ route("removeCategory") }}">
            @csrf
            <label for="removeCategory" class="font-bold">Supprimer une catégorie :</label>
            <select name="removeCategory" id="removeCategory" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                @foreach ($ressourceCategories as $categoryId => $category)
                    <option value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">Supprimer</button>
        </form>
    </div>
</div>

<!-- Partage Utilisateur -->
@if ($folder->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <div class="mx-auto max-w-5xl mb-6" x-data="{ open: false }">
        <button @click="open = !open" class="w-full text-left text-gray-900 font-bold py-2 px-4 bg-gray-200 rounded">Section Partage utilisateur</button>
        <div x-show="open" @click.away="open = false" class="p-4 bg-white border border-gray-300 rounded shadow mt-2">
            <p class="text-gray-700 mb-4">Vous pouvez partager ce dossier (et les notes et les dossiers qui sont à l'intérieur) à d'autres utilisateurs</p>
            <form action="{{ route("add_folder_share") }}" method="post">
                @csrf
                <label for="id_share" class="font-bold">Entrez l'identifiant de la personne à qui vous souhaitez partager la note :</label>
                <input name="id_share" type="number" min="0" class="w-full mt-1 p-2 border border-gray-300 rounded-md" />

                <label for="right" class="font-bold mt-4 block">Sélectionnez le droit que l'utilisateur aura sur la note</label>
                <select name="right" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                    <option value="RO">Lecture Seul (Read Only)</option>
                    <option value="RW">Lecture et Écriture</option>
                    <option value="F">Tout (Lecture, Écriture, Suppression, Renommer)</option>
                </select>
                <input type="hidden" name="folder_id" value="{{ $folder->id }}" />
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Envoyer</button>
            </form>
        </div>
    </div>

    <!-- Liste des autorisations utilisateurs -->
    <div class="mx-auto max-w-5xl p-4 bg-white rounded-lg shadow-md mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Liste des autorisations utilisateurs</h3>
        <table class="w-full border border-gray-300 rounded-lg">
            <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border border-gray-300">Nom d'utilisateur</th>
                <th class="p-2 border border-gray-300">ID de l'utilisateur</th>
                <th class="p-2 border border-gray-300">Droit</th>
                <th class="p-2 border border-gray-300">Action</th>
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
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

<script src="{{ asset('js/notification.js') }}"></script>
</body>
</html>

@include("includes.footer")
