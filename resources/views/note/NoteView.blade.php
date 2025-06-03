@include("includes.header")
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Éditeur de Note</title>
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
            .gradient-bg {
                background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            }
        </style>
        <script type="module" src="{{ asset('js/stack_edit/stack_edit_note.js') }}"></script>
        

        <script>
            var content = {!! json_encode($content) !!};
                @if(\Illuminate\Support\Facades\Auth::user()->id == $note->owner_id)
                    const perm = "F"; // L'utilisateur propriétaire à tout les droits
                @else
                    const perm = "{{ $perm_user->perm }}";
                @endif

                const csrf = '{{ csrf_token() }}';
                const note_id =  '{{ $note->id }}';
                const user_id = '{{ \Illuminate\Support\Facades\Auth::user()->id }}';
        </script>


        @vite('resources/css/app.css')
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

            <div class="mb-8 text-center animate-pop pt-2">
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 drop-shadow-lg mb-2">Éditeur de Note - {{ $note->name }}</h1>
                <p class="text-lg text-gray-600">Modifiez et organisez vos notes avec style !</p>
            </div>

            @if ($note->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
            <div class="arborescence flex flex-wrap items-center justify-center gap-2 mb-8 animate-pop">
                @php
                    $folder_tree = \App\Http\Controllers\NoteController::generateNoteTree($note->id);
                    $max = (count($folder_tree));
                @endphp
                @foreach ($folder_tree as $index => $folder_arbo)
                    @php
                        $isUserArbo = false;
                        $userArboName = null;
                        if (preg_match('/^user_(\\d+)$/', $folder_arbo['name'], $m)) {
                            $userArbo = \App\Models\User::find($m[1]);
                            if ($userArbo) {
                                $isUserArbo = true;
                                $userArboName = $userArbo->name;
                            }
                        }
                    @endphp
                    <a class="font-bold flex items-center text-blue-600 hover:text-pink-500 transition" href="@if($index == $max-1) {{route('note_view',$folder_arbo['id'])}} @else{{ route('folder_view', $folder_arbo['id']) }} @endif  ">
                        @if($index == 0) <span class="text-2xl mr-1">🏠</span> @elseif($index == $max-1) <span class="text-2xl mr-1">📝</span> @else <span class="text-2xl mr-1">📁</span> @endif
                        <span>{{ $isUserArbo ? $userArboName : $folder_arbo['name'] }}</span>
                    </a>
                    @if($index  < count($folder_tree) - 1)
                        <span class="text-gray-400">&gt;</span>
                    @endif
                @endforeach
            </div>
            @endif

            @if ($note->owner_id != \Illuminate\Support\Facades\Auth::user()->id)
                <div class="mb-6 text-center animate-pop">
                    <h3 class="font-bold text-pink-600">Vous consultez la note de {{ \App\Models\User::find($note->owner_id)->name }}</h3>
                    <h3 class="font-semibold text-gray-700">
                        Vous avez des droits :
                        @if ($perm_user->perm == "RO")
                            Lecture seule
                        @elseif ($perm_user->perm == "RW")
                            Lecture et écriture
                        @elseif ($perm_user->perm == "F")
                            Accès total
                        @endif
                        sur cette note
                    </h3>
                </div>
            @endif

            <!-- Éditeur StackEdit intégré, largeur max et hauteur confortable -->
            <div class="w-full  mx-auto flex justify-center mb-8 animate-pop">
        <div id="editor_md" class="w-full h-[700px] rounded-2xl shadow-xl border-2 border-blue-200 bg-white/90 transition-all duration-500" style="min-height:400px;">



        </div>

            </div>

            <div class="flex flex-col items-center justify-center mb-8 animate-pop">
                <span class="mb-2 text-gray-500">(La note est sauvegardée automatiquement au fur et à mesure de l'édition)</span>
                <form action="{{ route('note_pdf', ['note_id' => $note->id, 'user_id' =>  \Illuminate\Support\Facades\Auth::user()->id]) }}" method="post" class="flex justify-center">
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-blue-500 hover:to-green-500 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-all duration-300 hover:scale-105">
                        Télécharger le PDF
                    </button>
                    @csrf
                </form>
            </div>

            <!-- Catégories sobres et tamisées -->
            <div class="cat_display mb-8 animate-pop">
                <h2 class="text-2xl font-bold mb-4 text-blue-600 text-center">Liste des catégories</h2>
                <div class="flex flex-wrap gap-4 justify-center">
                    @foreach ($ressourceCategories as $category)
                        @php
                            $cat = \App\Models\Categorie::find($category->categorie_id);
                        @endphp
                        <div class="flex items-center px-6 py-2 rounded-full shadow-sm font-semibold text-gray-800 bg-white/80 border border-gray-200 transition-all duration-300 hover:shadow-md" style="min-width: 220px; min-height: 40px;">
                            <span class="w-5 h-5 border-2 border-white rounded-full mr-3" style="background: {{ $cat->color }};"></span>
                            <span class="font-medium">{{ $cat->category_name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

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
                        <input name="ressourceId" value="{{ $note->id }}" type="hidden" />
                        <input name="ressourceType" value="note" type="hidden" />
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
                @if ($note->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
                <div class="w-full md:w-1/2 bg-white/90 rounded-2xl shadow-xl p-6 flex flex-col items-center">
                    <h2 class="font-bold text-lg mb-4 text-pink-600">Partage utilisateur</h2>
                    <form action="{{ route('add_note_share') }}" method="post" class="w-full flex flex-col gap-4 items-center">
                        @csrf
                        <label for="id_share" class="font-bold">Identifiant de la personne à qui partager la note :</label>
                        <input name="id_share" type="number" min="0" class="border-2 border-pink-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-pink-400 focus:outline-none transition" />
                        <label for="right" class="font-bold">Droits de l'utilisateur :</label>
                        <select class="border-2 border-yellow-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-yellow-400 focus:outline-none transition" name="right">
                            <option value="RO">Lecture seule</option>
                            <option value="RW">Lecture et écriture</option>
                            <option value="F">Tout (Lecture, Écriture, Suppression, Renommer)</option>
                        </select>
                        <input type="hidden" name="note_id" value="{{ $note->id }}" />
                        <button type="submit" class="bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-yellow-400 hover:to-blue-500 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105">Partager</button>
                    </form>
                </div>
                @endif
            </div>

            @if ($note->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
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
        <script src="{{ asset('js/accordeon.js') }}"></script>
        <script src="{{ asset('js/notification.js') }}"></script>
        <script>
            @if(session('success'))
            showNotification("{{ session('success') }}", 'success');
            @elseif(session('failure'))
            showNotification("{{ session('success') }}", 'failure');
            @endif
        </script>
    </body>
</html>

@include("includes.footer")
