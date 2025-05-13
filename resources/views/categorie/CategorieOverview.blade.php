@include("includes.header")

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Liste des catégories - Zeus</title>
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
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 drop-shadow-lg mb-2">Gestion des Catégories</h1>
            <p class="text-lg text-gray-600">Crée, supprime et recherche tes catégories avec style !</p>
        </div>

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

        <!-- Ajout Catégorie -->
        <div class="add-task w-full bg-white/90 rounded-3xl shadow-2xl p-6 mb-8 flex flex-col items-center animate-pop">
            <h2 class="text-2xl font-bold mb-4 text-blue-600">Ajouter une catégorie</h2>
            <form action="{{ route('store_categorie') }}" method="POST" class="w-full flex flex-col gap-4 items-center">
                @csrf
                <input type="text" id="categorie_name" name="categorie_name" required placeholder="Nom de la catégorie" class="border-2 border-blue-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-blue-400 focus:outline-none transition" />
                <input type="color" name="color" required class="w-12 h-12 rounded-full border-2 border-blue-200 shadow-md cursor-pointer transition hover:scale-110" />
                <input type="submit" value="Créer la catégorie" class="bg-gradient-to-r from-blue-500 to-pink-500 hover:from-pink-500 hover:to-yellow-400 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105" />
            </form>
        </div>

        <!-- Liste des Catégories -->
        <div class="folders grid grid-cols-1 sm:grid-cols-2 gap-6 w-full mb-8">
            @foreach ($categories as $categorie)
                <div class="folder-card flex flex-col items-center p-6 bg-white/90 rounded-2xl shadow-xl relative overflow-hidden animate-pop group transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full opacity-20 blur-2xl gradient-bg"></div>
                    <div class="box w-16 h-16 rounded-full mb-4 shadow-lg border-4 border-white animate-pulse" style="background-color: {{ $categorie->color }}"></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">{{ $categorie->category_name }}</h3>
                    <div class="delete mt-2 w-full flex justify-center">
                        <form action="{{ route('delete_categorie') }}" method="post" class="w-full flex justify-center">
                            <input type="hidden" name="id" value="{{ $categorie->category_id }}" />
                            <button class="del bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all duration-200 hover:scale-110" type="submit">Supprimer</button>
                            @csrf
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recherche Catégorie -->
        <div class="Searchbar w-full bg-white/90 rounded-3xl shadow-2xl p-6 mt-4 flex flex-col items-center animate-pop">
            <h3 class="text-2xl font-bold mb-4 text-pink-600">Recherche par catégorie</h3>
            <form method="post" action="{{ route('searchCategory') }}" class="w-full flex flex-col gap-4 items-center">
                @csrf
                <select name="category" id="category" class="border-2 border-pink-200 rounded-lg py-2 px-4 w-full focus:ring-2 focus:ring-pink-400 focus:outline-none transition">
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->category_id }}">{{ $categorie->category_name }}</option>
                    @endforeach
                </select>
                <button class="bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-yellow-400 hover:to-blue-500 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition-all duration-300 hover:scale-105" type="submit">Rechercher</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/notification.js') }}"></script>
    <script>
        @if (session('success'))
        showNotification("{{ session('success') }}", 'success');
        @elseif (session('failure'))
        showNotification("{{ session('success') }}", 'failure');
        @endif
    </script>
</body>
</html>

@include("includes.footer")
