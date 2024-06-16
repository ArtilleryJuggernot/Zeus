<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("css/includes/header.css") }}">
    <link rel="stylesheet" href="{{ asset("css/includes/all.css") }}">
    <link rel="stylesheet" href="{{ asset("css/background/bg_day.css") }}">
    <link rel="stylesheet" href="{{ asset("css/animation/particule.css") }}">
    <link rel="icon" href="{{ url("img/favicon/favicon-32x32.png") }}">
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('img/logo-zeus.jpeg') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
</head>
<body class="bg-white">
<div class="header">
    <div class="logo">
        <a href="{{ route("home") }}">
            <img src="{{ asset("img/logo-zeus.jpeg") }}" alt="Zeus Logo" />
        </a>
    </div>

    <div class="user-info">
        Zeus Project -
        @if (Auth::user())
            {{ Auth::user()->name }}
        @else
            Visiteur
        @endif
    </div>

    <nav class="navbar">
        <ul>
            <li>
                <a href="{{ route("home") }}">🏠 Accueil</a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route("about") }}">
                            🔥 A propos du projet Zeus
                        </a>
                    </li>
                </ul>
            </li>

            @if (Auth::user())
                <li>
                    <a href="{{ route("folder_overview") }}">
                        📁 Mes dossiers
                    </a>
                    <ul class="submenu"></ul>
                </li>
            @endif

            @if (Auth::user())
                <li>
                    <a href="{{ route("notes_overview") }}">
                        📝 Mes notes
                    </a>
                    <ul class="submenu"></ul>
                </li>
            @endif

            @if (Auth::user())
                <li>
                    <a href="{{ route("task_overview") }}">
                        📚 Mes tâches
                    </a>
                    <ul class="submenu"></ul>
                </li>
            @endif

            @if (Auth::user())
                <li>
                    <a href="{{ route("projet_overview") }}">
                        🚧 Mes Projets
                    </a>
                    <ul class="submenu"></ul>
                </li>
            @endif

            @if (\Illuminate\Support\Facades\Auth::user())
                <li>
                    <a href="{{ route("categorie_overview") }}">
                        📌 Mes categories
                    </a>
                </li>
            @endif

            @if (Auth::user())
                <li>
                    <a>🚧 Mes modules</a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route("livre_overview") }}">
                                📚 Mes Livres
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("habitude_overview") }}">
                                🏆 Mes Habitudes
                            </a>
                        </li>




                    </ul>
                </li>
            @endif

            @if (Auth::user())
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Se déconnecter
                    </a></li>
            @else
                <li><a href="{{ route("login") }}">Se connecter</a></li>
                <li>
                    <a href="{{ route("register") }}">S'enregister !</a>
                </li>
            @endif

            @if (Auth::user())
                <li>
                    <a href="{{ route("profile", Auth::user()->id) }}">
                        Mon profil
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route("weekly_stats") }}">
                                Statistiques de la semaine
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->id == 1)
                <li>
                    <a>👑 Administration</a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route("user_manage") }}">
                                👑 Gestion des utilisateurs
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("logs_manage") }}">
                                👑 Gestion des logs
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</div>

<form
    id="logout-form"
    action="{{ route("logout") }}"
    method="POST"
    style="display: none"
>
    @csrf
</form>





@if (\Illuminate\Support\Facades\Auth::user())
    @include("includes.search.searchbar")
@endif

<script src="{{ asset('/sw.js') }}"></script>
<script>
    if ("serviceWorker" in navigator) {
        // Register a service worker hosted at the root of the
        // site using the default scope.
        navigator.serviceWorker.register("/sw.js").then(
            (registration) => {
                console.log("Service worker registration succeeded:", registration);
            },
            (error) => {
                console.error(`Service worker registration failed: ${error}`);
            },
        );
    } else {
        console.error("Service workers are not supported.");
    }
</script>

</body>
</html>
