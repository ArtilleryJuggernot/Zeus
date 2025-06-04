@include('includes.header')

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditeur de Tâche - {{ $task->task_name }} - Zeus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/notification/notification.css') }}">
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/accordeon.js') }}"></script>
    <script src="{{ asset('js/shortcut_editor.js') }}"></script>
    <script type="module" src="{{ asset('js/stack_edit/stack_edit_task.js') }}?v{{filemtime(public_path('js/stack_edit/stack_edit_task.js')) }}"></script>
    <style>
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: pop 0.5s cubic-bezier(.4,0,.2,1) both; }
        .gradient-bg {
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-pink-50 to-yellow-50 min-h-screen flex flex-col items-center justify-center py-8">
<div class="w-full  mx-auto">
    @if ($errors->any())
        <div class="p-4 mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 rounded animate-pop">
            <h2 class="font-bold mb-2">Il y a eu des erreurs</h2>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    <script>
    var content = {!! json_encode($task->description) !!};
    @if(\Illuminate\Support\Facades\Auth::user()->id == $task->owner_id)
        const perm = "F"; // L'utilisateur propriétaire à tout les droits
    @else
        const perm = "{{$perm_user->perm }}";
    @endif

    const csrf = '{{csrf_token()}}';
    const task_id =  '{{ $task->id}}';
    const user_id = '{{\Illuminate\Support\Facades\Auth::user()->id}}';
</script>

    <div class="mb-8 text-center animate-pop pt-2">
        <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 drop-shadow-lg mb-2">Éditeur de Tâche - {{ $task->task_name }}</h1>
        <p class="text-lg text-gray-600">Gérez et organisez vos tâches avec style !</p>
    </div>

    <!-- Description/éditeur markdown -->
    <div class="w-full flex justify-center mb-8 animate-pop">
        <div id="editor_md" class="w-full h-[700px] rounded-2xl shadow-xl border-2 border-blue-200 bg-white/90 transition-all duration-500" style="min-height:200px;"></div>
    </div>

    <!-- Encadré infos tâche (sous l'éditeur) -->
    <div class="mb-8 animate-pop flex flex-col md:flex-row gap-6 items-center justify-center">
        <div class="bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col gap-4 min-w-[260px] w-full md:w-1/2 border-2 border-blue-200">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold text-blue-700">Statut :</span>
                <span class="ml-2 px-3 py-1 rounded-full text-xs font-bold shadow {{ $task->is_finish ? 'bg-green-200 text-green-700' : 'bg-blue-200 text-blue-700' }}">
                    {{ $task->is_finish ? 'Terminée' : 'En cours' }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="font-bold text-pink-600">Date limite :</span>
                <form action="{{ route('update_task_quick') }}" method="POST" class="flex items-center gap-2 ml-2">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="date" name="dt_input" value="{{ $task->due_date }}" class="border border-gray-300 rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-pink-400 transition text-sm" />
                    <input type="hidden" name="tache_name" value="{{ $task->task_name }}">
                    <input type="hidden" name="priority" value="{{ optional(\App\Models\task_priorities::where('task_id', $task->id)->first())->priority ?? 'None' }}">
                    <input type="hidden" name="is_due" value="on">
                    <button type="submit" class="ml-2 px-2 py-1 rounded bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Modifier</button>
                </form>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17.75L18.2 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <span class="font-bold text-yellow-600">Priorité :</span>
                <form action="{{ route('update_task_quick') }}" method="POST" class="flex items-center gap-2 ml-2">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="tache_name" value="{{ $task->task_name }}">
                    <input type="hidden" name="dt_input" value="{{ $task->due_date }}">
                    <select name="priority" class="border border-gray-300 rounded-md py-1 px-2 focus:ring-2 focus:ring-yellow-400 text-sm">
                        <option value="None" {{ !optional(\App\Models\task_priorities::where('task_id', $task->id)->first())->priority ? 'selected' : '' }}>Aucune</option>
                        <option value="Urgence" {{ optional(\App\Models\task_priorities::where('task_id', $task->id)->first())->priority == 'Urgence' ? 'selected' : '' }}>🔥 Urgence</option>
                        <option value="Grande priorité" {{ optional(\App\Models\task_priorities::where('task_id', $task->id)->first())->priority == 'Grande priorité' ? 'selected' : '' }}>⚡ Grande priorité</option>
                        <option value="Prioritaire" {{ optional(\App\Models\task_priorities::where('task_id', $task->id)->first())->priority == 'Prioritaire' ? 'selected' : '' }}>⭐ Prioritaire</option>
                    </select>
                    <button type="submit" class="ml-2 px-2 py-1 rounded bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-bold flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Modifier</button>
                </form>
            </div>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($ressourceCategories as $category)
                    @php $cat = \App\Models\Categorie::find($category->categorie_id); @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold shadow" style="background: {{ $cat->color ?? '#3b82f6' }}; color: white;">{{ $cat->category_name }}</span>
                @endforeach
            </div>
        </div>
        <div class="flex-1 flex flex-col items-center justify-center gap-4">
            <form action="{{ route('update_task_finish') }}" method="POST" class="w-full flex justify-center">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}">
                <input type="hidden" name="task_completed" value="{{ $task->is_finish ? 'off' : 'on' }}">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition flex items-center gap-2 text-lg w-full justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    {{ $task->is_finish ? 'Marquer comme en cours' : 'Valider la tâche' }}
                </button>
            </form>
            <button type="button" onclick="openDeleteModal()" class="bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition flex items-center gap-2 text-lg w-full justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                Supprimer la tâche
            </button>
        </div>
    </div>

    <!-- Modale de confirmation suppression -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md relative animate-pop border border-red-200 z-10 flex flex-col items-center">
            <button onclick="closeDeleteModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4 text-red-700 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                Confirmation de suppression
            </h3>
            <p class="mb-6 text-gray-700 text-center">Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est <span class="font-bold text-red-600">irréversible</span>.</p>
            <form action="{{ route('delete_task') }}" method="POST" class="w-full flex flex-col items-center gap-4">
                @csrf
                <input type="hidden" name="id" value="{{ $task->id }}">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition flex items-center gap-2 text-lg w-full justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    Oui, supprimer définitivement
                </button>
            </form>
            <button onclick="closeDeleteModal()" class="mt-2 px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold">Annuler</button>
        </div>
    </div>

    <!-- Gestion des catégories -->
    <div class="w-full flex flex-col md:flex-row gap-6 mb-8 animate-pop">
        <div class="w-full md:w-1/2 bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col items-center">
            <h2 class="font-bold text-lg mb-4 text-blue-600">Gestion des catégories</h2>
            <form method="post" action="{{ route('addCategory') }}" class="w-full flex flex-col gap-4 items-center">
                @csrf
                <select class="border-2 border-blue-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-blue-400 focus:outline-none transition" name="category" id="category">
                    @foreach ($notOwnedCategories as $categoryId => $categoryName)
                        <option value="{{ $categoryId }}">{{ $categoryName }}</option>
                    @endforeach
                </select>
                <input name="ressourceId" value="{{ $task->id }}" type="hidden" />
                <input name="ressourceType" value="task" type="hidden" />
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-pink-500 hover:from-pink-500 hover:to-yellow-400 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Ajouter</button>
            </form>
            <form method="post" action="{{ route('removeCategory') }}" class="w-full flex flex-col gap-4 items-center mt-4">
                @csrf
                <select class="border-2 border-pink-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-pink-400 focus:outline-none transition" name="removeCategory" id="removeCategory">
                    @foreach ($ressourceCategories as $categoryId => $category)
                        <option value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-yellow-400 hover:to-blue-500 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Supprimer</button>
            </form>
        </div>
        @if ($task->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
        <div class="w-full md:w-1/2 bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col items-center">
            <h2 class="font-bold text-lg mb-4 text-pink-600">Partage utilisateur</h2>
            <form action="{{ route('add_task_share') }}" method="post" class="w-full flex flex-col gap-4 items-center">
                @csrf
                <label for="id_share" class="font-bold">Identifiant de la personne à qui partager la tâche :</label>
                <input name="id_share" type="number" min="0" class="border-2 border-pink-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-pink-400 focus:outline-none transition" />
                <label for="right" class="font-bold">Droits de l'utilisateur :</label>
                <select class="border-2 border-yellow-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-yellow-400 focus:outline-none transition" name="right">
                    <option value="RO">Lecture seule</option>
                    <option value="RW">Lecture et écriture</option>
                    <option value="F">Tout (Lecture, Écriture, Suppression, Renommer)</option>
                </select>
                <input type="hidden" name="task_id" value="{{ $task->id }}" />
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-yellow-400 hover:to-blue-500 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Partager</button>
            </form>
        </div>
        @endif
    </div>

    @if ($task->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <div class="w-full bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col items-center mb-8 animate-pop">
        <h2 class="font-bold text-lg mb-4 text-yellow-600">Liste des autorisations utilisateurs</h2>
        <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead>
            <tr class="bg-gradient-to-r from-blue-100 to-pink-100">
                <th class="p-2 border border-gray-300">Nom d'utilisateur</th>
                <th class="p-2 border border-gray-300">ID utilisateur</th>
                <th class="p-2 border border-gray-300">Droit</th>
                <th class="p-2 border border-gray-300">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($usersPermissionList as $perm)
                <tr class="hover:bg-yellow-50 transition">
                    <td class="p-2 border border-gray-300">{{ \App\Models\User::find($perm->dest_id)->name }}</td>
                    <td class="p-2 border border-gray-300">{{ $perm->dest_id }}</td>
                    <td class="p-2 border border-gray-300">{{ $perm->perm }}</td>
                    <td class="p-2 border border-gray-300">
                        <form action="{{ route('delete_perm', ['id' => $perm->id]) }}" method="POST" class="flex justify-center">
                            @csrf
                            <button type="submit" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-pink-500 hover:to-yellow-400 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all duration-200 hover:scale-110">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
<script>
    @if(session('success'))
    showNotification("{{ session('success') }}", 'success');
    @elseif(session('failure'))
    showNotification("{{ session('success') }}", 'failure');
    @endif

    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@include("includes.footer")
