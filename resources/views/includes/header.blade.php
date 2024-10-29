<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ url('img/favicon/favicon-32x32.png') }}">
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('img/logo-zeus.jpeg') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-white">
<header class="bg-gray-800 text-white shadow-md">
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo-zeus.jpeg') }}" alt="Zeus Logo" class="h-14 w-14">

                </a>
                <a href="{{ route('home') }}">
                    <span class="ml-2 text-xl font-bold">Zeus Project</span>
                </a>
            </div>

            <!-- Navigation Menu -->
            <div class="hidden md:flex md:items-center">
                <nav class="ml-10 flex items-baseline space-x-4">
                    <!-- Accueil avec sous-menu -->
                    <div class="relative group">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 flex items-center whitespace-nowrap">
                            🏠 Accueil
                        </a>
                        <div class="absolute left-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('about') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">🔥 À propos de Zeus</a>
                        </div>
                    </div>

                    @if (Auth::user())
                        <!-- Mes dossiers -->
                        <div class="relative group">
                            <a href="{{ route('folder_overview') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 whitespace-nowrap">
                                📁 Mes dossiers
                            </a>
                        </div>

                        <!-- Mes notes -->
                        <div class="relative group">
                            <a href="{{ route('notes_overview') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 whitespace-nowrap">
                                📝 Mes notes
                            </a>
                        </div>

                        <!-- Mes tâches -->
                        <div class="relative group">
                            <a href="{{ route('task_overview') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 whitespace-nowrap">
                                📚 Mes tâches
                            </a>
                        </div>

                        <!-- Mes projets -->
                        <div class="relative group">
                            <a href="{{ route('projet_overview') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 whitespace-nowrap">
                                🚧 Mes Projets
                            </a>
                        </div>

                        <!-- Mes catégories -->
                        <div class="relative group">
                            <a href="{{ route('categorie_overview') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 whitespace-nowrap">
                                📌 Mes catégories
                            </a>
                        </div>

                        <!-- Mes modules avec sous-menu -->
                        <div class="relative group">
                            <a href="#" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 flex items-center whitespace-nowrap">
                                🚧 Mes modules
                            </a>
                            <div class="absolute left-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                <a href="{{ route('livre_overview') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 whitespace-nowrap">📚 Mes Livres</a>
                                <a href="{{ route('habitude_overview') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 whitespace-nowrap">🏆 Mes Habitudes</a>
                            </div>
                        </div>

                        <!-- Administration avec sous-menu -->
                        @if (Auth::user()->id == 1)
                            <div class="relative group">
                                <a href="#" class="px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 flex items-center whitespace-nowrap">
                                    👑 Administration
                                </a>
                                <div class="absolute left-0 mt-2 w-60 bg-gray-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                    <a href="{{ route('user_manage') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 whitespace-nowrap">👑 Gestion des utilisateurs</a>
                                    <a href="{{ route('logs_manage') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 whitespace-nowrap">👑 Gestion des logs</a>
                                </div>
                            </div>
                        @endif


                    @endif
                </nav>
            </div>

            <!-- Photo de profil ou initiale -->
            <div class="flex items-center">
                @if (Auth::user())
                    <div class="ml-20 relative group w-16">
                        <a href="{{ route('profile', Auth::user()->id) }}" class="flex items-center">
                            @php
                                $profilePath = 'storage/' . \Illuminate\Support\Facades\Auth::user()->id . '.png';
                                $hasProfilePic = \Illuminate\Support\Facades\Storage::exists("public/" . \Illuminate\Support\Facades\Auth::user()->id . '.png');
                                $initial = strtoupper(substr(\Illuminate\Support\Facades\Auth::user()->name, 0, 1));
                            @endphp
                            @if ($hasProfilePic)
                                <img src="{{ asset($profilePath) }}" alt="Profile Picture" class="w-10 h-10 rounded-full border-2 border-white">
                            @else
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-lg font-semibold">
                                    {{ $initial }}
                                </div>
                            @endif
                        </a>
                        <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('weekly_stats') }}" class="block px-4 py-2 text-sm hover:bg-gray-700">Statistiques de la semaine</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-700" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Se déconnecter
                            </a>
                        </div>

                    </div>
                @endif
            </div>

            <!-- Menu Hamburger pour mobile -->
            <div class="flex items-center md:hidden">
                <button id="menu-toggle" class="text-white focus:outline-none">
                    ☰
                </button>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-800">
        <nav class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
            <!-- Accueil avec sous-menu -->
            <div class="relative group">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">🏠 Accueil</a>
                <div class="hidden group-hover:block bg-gray-700 rounded-md shadow-lg">
                    <a href="{{ route('about') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">🔥 À propos de Zeus</a>
                </div>
            </div>

            @if (Auth::user())
                <a href="{{ route('folder_overview') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">📁 Mes dossiers</a>
                <a href="{{ route('notes_overview') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">📝 Mes notes</a>
                <a href="{{ route('task_overview') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">📚 Mes tâches</a>
                <a href="{{ route('projet_overview') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">🚧 Mes Projets</a>
                <a href="{{ route('categorie_overview') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">📌 Mes catégories</a>

                <!-- Mes modules avec sous-menu -->
                <div class="relative group">
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">🚧 Mes modules</a>
                    <div class="hidden group-hover:block bg-gray-700 rounded-md shadow-lg">
                        <a href="{{ route('livre_overview') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">📚 Mes Livres</a>
                        <a href="{{ route('habitude_overview') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">🏆 Mes Habitudes</a>
                    </div>
                </div>

                <!-- Administration avec sous-menu -->
                @if (Auth::user()->id == 1)
                    <div class="relative group">
                        <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">👑 Administration</a>
                        <div class="hidden group-hover:block bg-gray-700 rounded-md shadow-lg">
                            <a href="{{ route('user_manage') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">👑 Gestion des utilisateurs</a>
                            <a href="{{ route('logs_manage') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">👑 Gestion des logs</a>
                        </div>
                    </div>
                @endif

                <!-- Se déconnecter -->
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Se déconnecter</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">Se connecter</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">S'enregistrer !</a>
            @endif

            @if (Auth::user())
                <div class="relative group">
                    <a href="{{ route('profile', Auth::user()->id) }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300">
                        @php
                            $profilePath = 'storage/' . Auth::user()->id . '.png';
                            $hasProfilePic = Storage::exists($profilePath);
                            $initial = strtoupper(substr(Auth::user()->name, 0, 1));
                        @endphp
                        @if ($hasProfilePic)
                            <img src="{{ asset($profilePath) }}" alt="Profile Picture" class="w-8 h-8 rounded-full border-2 border-white mr-2">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-semibold">
                                {{ $initial }}
                            </div>
                        @endif
                    </a>
                    <div class="hidden group-hover:block bg-gray-700 rounded-md shadow-lg">
                        <a href="{{ route('weekly_stats') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Statistiques de la semaine</a>
                    </div>
                </div>
            @endif
        </nav>
    </div>
</header>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Barre de recherche -->
@if (Auth::user())
    @include('includes.search.searchbar')
@endif

<!-- Service Worker -->
<script src="{{ asset('/sw.js') }}"></script>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('Service worker registration succeeded:', registration);
            })
            .catch(error => {
                console.error(`Service worker registration failed: ${error}`);
            });
    } else {
        console.error('Service workers are not supported.');
    }

    // JavaScript pour le menu hamburger
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
</body>
</html>

