<style>
    /* Effet glassmorphism et ombre douce */
    .glass {
        background: rgba(0, 0, 0, 0.85);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        backdrop-filter: blur(8px);
        border-radius: 1.5rem;
    }
    .submenu {
        transition: opacity 0.3s, visibility 0.3s;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
    .group:hover .submenu, .group:focus-within .submenu, .submenu:focus-within {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .submenu {
        z-index: 50;
    }
    .submenu:hover, .submenu:focus {
        opacity: 1;
        visibility: visible;
    }
</style>
@vite('resources/css/app.css')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="{{ asset('js/app.js') }}"></script>
<header class="glass text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo-zeus.jpeg') }}" alt="Zeus Logo" class="h-16 w-16 rounded-2xl shadow-lg border-2 border-yellow-400">
                </a>
                <a href="{{ route('home') }}">
                    <span class="ml-2 text-2xl font-extrabold tracking-tight bg-gradient-to-r from-yellow-300 via-pink-400 to-blue-400 text-transparent bg-clip-text drop-shadow">⚡ Zeus Project</span>
                </a>
            </div>
            <!-- Navigation Menu -->
            <nav class="hidden md:flex md:items-center gap-2">
                <ul class="flex items-center gap-2">
                    <!-- Accueil avec sous-menu -->
                    <li class="relative group">
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-yellow-400/20 flex items-center gap-1 whitespace-nowrap transition">🏠 Accueil</a>
                        <div class="submenu absolute left-0 mt-2 w-56 bg-gray-900/95 rounded-2xl shadow-2xl p-2 flex flex-col gap-1 border border-blue-400">
                            <a href="{{ route('about') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-blue-700/40 transition">🔥 À propos de Zeus</a>
                        </div>
                    </li>
                    @if (Auth::user())
                        <li><a href="{{ route('folder_overview') }}" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20 whitespace-nowrap transition">📁 Mes dossiers</a></li>
                        <li><a href="{{ route('notes_overview') }}" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-pink-400/20 whitespace-nowrap transition">📝 Mes notes</a></li>
                        <li><a href="{{ route('task_overview') }}" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-green-400/20 whitespace-nowrap transition">📚 Mes tâches</a></li>
                        <li><a href="{{ route('projet_overview') }}" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-yellow-400/20 whitespace-nowrap transition">🚧 Mes Projets</a></li>
                        <li><a href="{{ route('categorie_overview') }}" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20 whitespace-nowrap transition">📌 Mes catégories</a></li>
                        <!-- Mes modules avec sous-menu -->
                        <li class="relative group">
                            <a href="#" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-pink-400/20 flex items-center gap-1 whitespace-nowrap transition">🚀 Mes modules <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></a>
                            <div class="submenu absolute left-0 mt-2 w-56 bg-gray-900/95 rounded-2xl shadow-2xl p-2 flex flex-col gap-1 border border-pink-400">
                                <a href="{{ route('livre_overview') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-blue-700/40 transition">📚 Mes Livres</a>
                                <a href="{{ route('habitude_overview') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-yellow-700/40 transition">🏆 Mes Habitudes</a>
                            </div>
                        </li>
                        <!-- Administration avec sous-menu -->
                        @if (Auth::user()->id == 1)
                            <li class="relative group">
                                <a href="#" class="px-4 py-2 rounded-xl text-base font-semibold hover:bg-yellow-400/20 flex items-center gap-1 whitespace-nowrap transition">👑 Administration <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></a>
                                <div class="submenu absolute left-0 mt-2 w-64 bg-gray-900/95 rounded-2xl shadow-2xl p-2 flex flex-col gap-1 border border-yellow-400">
                                    <a href="{{ route('user_manage') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-blue-700/40 transition">👑 Gestion des utilisateurs</a>
                                    <a href="{{ route('logs_manage') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-pink-700/40 transition">👑 Gestion des logs</a>
                                    <a href="{{ route('admin.settings') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-yellow-700/40 transition">⚙️ Paramètres du site</a>
                                </div>
                            </li>
                        @endif
                    @endif
                </ul>
            </nav>
            <!-- Profil utilisateur -->
            <div class="flex items-center">
                @if (Auth::user())
                    <div class="ml-10 relative group w-16">
                        <a href="{{ route('profile', Auth::user()->id) }}" class="flex items-center">
                            @php
                                $profilePath = 'storage/' . \Illuminate\Support\Facades\Auth::user()->id . '.png';
                                $hasProfilePic = \Illuminate\Support\Facades\Storage::exists("public/" . \Illuminate\Support\Facades\Auth::user()->id . '.png');
                                $initial = strtoupper(substr(\Illuminate\Support\Facades\Auth::user()->name, 0, 1));
                            @endphp
                            @if ($hasProfilePic)
                                <img src="{{ asset($profilePath) }}" alt="Profile Picture" class="w-12 h-12 rounded-full border-2 border-white shadow">
                            @else
                                <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-semibold shadow">
                                    {{ $initial }}
                                </div>
                            @endif
                        </a>
                        <div class="submenu absolute right-0 mt-2 w-56 bg-gray-900/95 rounded-2xl shadow-2xl p-2 flex flex-col gap-1 border border-blue-400">
                            <a href="{{ route('weekly_stats') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-blue-700/40 transition">📊 Statistiques de la semaine</a>
                            <a href="#" class="block px-4 py-2 rounded-lg text-sm hover:bg-red-700/40 transition" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Se déconnecter</a>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Menu Hamburger pour mobile -->
            <div class="flex items-center md:hidden">
                <button id="menu-toggle" class="text-white focus:outline-none text-3xl">
                    ☰
                </button>
            </div>
        </div>
    </div>
    <!-- Menu mobile -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-900/95 glass rounded-b-2xl shadow-2xl">
        <nav class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-yellow-400/20">🏠 Accueil</a>
            <a href="{{ route('about') }}" class="block px-4 py-2 text-sm hover:bg-blue-700/40">🔥 À propos de Zeus</a>
            @if (Auth::user())
                <a href="{{ route('folder_overview') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20">📁 Mes dossiers</a>
                <a href="{{ route('notes_overview') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-pink-400/20">📝 Mes notes</a>
                <a href="{{ route('task_overview') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-green-400/20">📚 Mes tâches</a>
                <a href="{{ route('projet_overview') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-yellow-400/20">🚧 Mes Projets</a>
                <a href="{{ route('categorie_overview') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20">📌 Mes catégories</a>
                <div class="mt-2">
                    <span class="block px-3 py-2 text-base font-semibold text-yellow-300">🚀 Mes modules</span>
                    <a href="{{ route('livre_overview') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-blue-700/40">📚 Mes Livres</a>
                    <a href="{{ route('habitude_overview') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-yellow-700/40">🏆 Mes Habitudes</a>
                </div>
                @if (Auth::user()->id == 1)
                    <div class="mt-2">
                        <span class="block px-3 py-2 text-base font-semibold text-yellow-300">👑 Administration</span>
                        <a href="{{ route('user_manage') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-blue-700/40">👑 Gestion des utilisateurs</a>
                        <a href="{{ route('logs_manage') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-pink-700/40">👑 Gestion des logs</a>
                        <a href="{{ route('admin.settings') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-yellow-700/40">⚙️ Paramètres du site</a>
                    </div>
                @endif
                <a href="{{ route('profile', Auth::user()->id) }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20">👤 Mon profil</a>
                <a href="{{ route('weekly_stats') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20">📊 Statistiques de la semaine</a>
                <a href="#" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-red-700/40" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Se déconnecter</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-blue-400/20">Se connecter</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-xl text-base font-semibold hover:bg-pink-400/20">S'enregistrer !</a>
            @endif
        </nav>
    </div>
</header>
<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
@if (Auth::user())
    @include('includes.search.searchbar')
@endif
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
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
    document.querySelectorAll('.group').forEach(function(group) {
        let timeout;
        group.addEventListener('mouseenter', function() {
            clearTimeout(timeout);
            const submenu = group.querySelector('.submenu');
            if(submenu) submenu.classList.add('!opacity-100', '!visible');
        });
        group.addEventListener('mouseleave', function() {
            const submenu = group.querySelector('.submenu');
            timeout = setTimeout(function() {
                if(submenu) submenu.classList.remove('!opacity-100', '!visible');
            }, 350);
        });
        const submenu = group.querySelector('.submenu');
        if(submenu) {
            submenu.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
            });
            submenu.addEventListener('mouseleave', function() {
                timeout = setTimeout(function() {
                    submenu.classList.remove('!opacity-100', '!visible');
                }, 350);
            });
        }
    });
</script>

