@include("includes.header")

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>📚 Mes Livres - Module Lecture</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" />
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

<!-- Notification animée -->
<div id="notification" class="fixed top-24 right-6 bg-green-500 text-white font-bold py-2 px-4 rounded shadow-lg transition-opacity duration-300 opacity-0 z-50 flex items-center space-x-2">
    <span id="notif-emoji">✅</span>
    <span id="notif-text"></span>
</div>

<div class="mx-auto max-w-5xl p-4 animate-pop">
    <!-- Titre principal -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-center mb-2 bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 text-transparent bg-clip-text drop-shadow-lg flex items-center justify-center gap-2">📚 Module Lecture - Mes Livres</h1>
    <p class="text-lg text-center text-gray-500 mb-8">Gérez vos lectures, découpez vos livres en étapes, et progressez chaque jour !</p>

    <!-- Formulaire d'ajout de livre -->
    <div class="w-full max-w-2xl mx-auto bg-white/90 rounded-2xl shadow-xl p-8 border-2 border-blue-200 mb-10 animate-pop">
        <h2 class="font-bold text-2xl mb-4 text-blue-700 flex items-center gap-2">➕ Ajouter un livre à lire</h2>
        <form action="{{ route('store_livre') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="livre_name" class="font-bold flex items-center gap-2">
                    <span class="text-xl">📖</span> Nom du livre :
                </label>
                <input type="text" id="livre_name" name="livre_name" required minlength="1" maxlength="250" class="border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full shadow-sm" placeholder="Ex : Le Petit Prince" />
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="startPage" class="font-bold flex items-center gap-2">📄 Page de début :</label>
                    <input type="number" min="0" value="0" id="startPage" name="startPage" class="border border-gray-300 rounded-lg py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex-1">
                    <label for="endPage" class="font-bold flex items-center gap-2">📄 Page de fin :</label>
                    <input type="number" min="0" id="endPage" name="endPage" class="border border-gray-300 rounded-lg py-2 px-4 w-full focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <div>
                <label for="delta" class="font-bold flex items-center gap-2">⏳ En combien de temps souhaitez-vous finir le livre ?</label>
                <div class="flex flex-col md:flex-row gap-4">
                    <input type="number" min="1" id="delta_num" name="dt_num" class="border border-gray-300 rounded-lg py-2 px-4 w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Nombre" />
                    <select name="delta_type" id="category" class="border border-gray-300 rounded-lg py-2 px-4 w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="jours">jours</option>
                        <option value="semaines">semaines</option>
                        <option value="mois">mois</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                <span class="text-xl">🚀</span> Commencer le livre
            </button>
        </form>
    </div>

    <!-- Liste des livres en cours -->
    <h3 class="font-bold text-2xl mb-6 text-blue-700 flex items-center gap-2">📚 Livres en cours</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @forelse ($userLivreUnDone as $projet)
            <div class="bg-gradient-to-br from-blue-100 via-pink-50 to-yellow-50 border-2 border-blue-300 rounded-2xl shadow-xl p-6 flex flex-col gap-4 hover:scale-105 transition-transform duration-200 animate-pop relative">
                <a href="{{ route('projet_view', $projet->id) }}" class="flex items-center text-2xl font-bold text-blue-700 hover:underline gap-2">
                    <span>📖</span> <span>{{ $projet->name }}</span>
                </a>
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
        @empty
            <div class="col-span-3 text-center text-gray-500 text-lg">Aucun livre en cours pour le moment. Ajoutez-en un !</div>
        @endforelse
    </div>

    <!-- Liste des livres terminés -->
    <h3 class="font-bold text-2xl mb-6 text-green-700 flex items-center gap-2">🏁 Livres terminés</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @forelse ($userLivreDone as $projet)
            <div class="bg-gradient-to-br from-green-100 via-blue-50 to-yellow-50 border-2 border-green-300 rounded-2xl shadow-xl p-6 flex flex-col gap-4 hover:scale-105 transition-transform duration-200 animate-pop relative">
                <a href="{{ route('projet_view', $projet->id) }}" class="flex items-center text-2xl font-bold text-green-700 hover:underline gap-2">
                    <span>🏁</span> <span>{{ $projet->name }}</span>
                </a>
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
        @empty
            <div class="col-span-3 text-center text-gray-500 text-lg">Aucun livre terminé pour le moment.</div>
        @endforelse
    </div>
</div>

<script src="{{ asset('js/notification.js') }}"></script>
<script>
    // Notification animée
    function showNotification(message, type = 'success') {
        const notif = document.getElementById('notification');
        const notifText = document.getElementById('notif-text');
        const notifEmoji = document.getElementById('notif-emoji');
        notifText.textContent = message;
        notifEmoji.textContent = type === 'success' ? '✅' : '❌';
        notif.classList.remove('opacity-0');
        notif.classList.add('opacity-100');
        setTimeout(() => {
            notif.classList.remove('opacity-100');
            notif.classList.add('opacity-0');
        }, 3000);
    }
    @if (session('success'))
    showNotification("{{ session('success') }}", 'success');
    @elseif (session('error'))
    showNotification("{{ session('error') }}", 'failure');
    @endif
</script>

@include("includes.footer")
</body>
</html>
