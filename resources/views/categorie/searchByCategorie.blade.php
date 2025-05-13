@include("includes.header")
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Recherche de catégories - Zeus</title>
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
    </head>
    <body class="bg-gradient-to-br from-blue-50 via-pink-50 to-yellow-50 min-h-screen flex flex-col items-center justify-center py-8">
        <div class="w-full max-w-2xl mx-auto">
            <div class="mb-8 text-center animate-pop">
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 drop-shadow-lg mb-2">Résultat des recherches</h1>
                <p class="text-lg text-gray-600">Voici les ressources trouvées pour cette catégorie</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full">
                @forelse ($ressources as $r)
                    <div class="folder-card flex flex-col items-center p-6 bg-white/90 rounded-2xl shadow-xl relative overflow-hidden animate-pop group transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full opacity-20 blur-2xl gradient-bg"></div>
                        @if ($r->type_ressource == "note")
                            <a href="{{ route('note_view', $r->ressource_id) }}" class="w-full flex flex-col items-center">
                                <div class="text-yellow-400 text-5xl mb-2 group-hover:scale-110 transition-transform duration-200">📝</div>
                                <h3 class="text-lg font-bold text-gray-800 text-center">{{ \App\Models\Note::find($r->ressource_id)->name }}</h3>
                            </a>
                        @endif
                        @if ($r->type_ressource == "task")
                            <a href="{{ route('view_task', $r->ressource_id) }}" class="w-full flex flex-col items-center">
                                <div class="text-green-500 text-5xl mb-2 group-hover:scale-110 transition-transform duration-200">📚</div>
                                <h3 class="text-lg font-bold text-gray-800 text-center">{{ \App\Models\Task::find($r->ressource_id)->task_name }}</h3>
                            </a>
                        @endif
                        @if ($r->type_ressource == "folder")
                            <a href="{{ route('folder_view', $r->ressource_id) }}" class="w-full flex flex-col items-center">
                                <div class="text-blue-500 text-5xl mb-2 group-hover:scale-110 transition-transform duration-200">📁</div>
                                <h3 class="text-lg font-bold text-gray-800 text-center">{{ \App\Models\Folder::find($r->ressource_id)->name }}</h3>
                            </a>
                        @endif
                        @if ($r->type_ressource == "project")
                            <a href="{{ route('projet_view', $r->ressource_id) }}" class="w-full flex flex-col items-center">
                                <div class="text-pink-500 text-5xl mb-2 group-hover:scale-110 transition-transform duration-200">🗂️</div>
                                <h3 class="text-lg font-bold text-gray-800 text-center">{{ \App\Models\Projet::find($r->ressource_id)->name }}</h3>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center text-2xl font-bold text-gray-400 animate-pop">Aucune ressource trouvée pour cette catégorie.</div>
                @endforelse
            </div>
        </div>
    </body>
</html>

@include("includes.footer")
