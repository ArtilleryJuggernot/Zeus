<head>
    <link rel="stylesheet" href="/css/includes/header.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
</head>
<div class="header">
    <!-- Logo du jeu -->
    <div class="logo">
        <!-- Insérer le logo du jeu -->
        <a href="{{route('home')}}"> <img src="/img/logo-zeus.jpeg" alt="Zeus Logo"> </a>
    </div>

    <!-- Informations de l'utilisateur -->
    <div class="user-info">
        Zeus Project -
        @if(Auth::user())
            {{Auth::user()->name}}
        @else
            Visiteur
        @endif


    </div>

    <!-- Barre de navigation -->
    <nav class="navbar">
        <ul>
            <li>
                <a href="{{ route('home') }}">Accueil</a>
                <ul class="submenu">
                    <li><a href="{{ route('about') }}">A propos du projet Zeus</a></li>
                </ul>
            </li>

            @if(Auth::user())
                <li>
                    <a href="{{ route("folder_overview") }}">Mes dossiers</a>
                    <ul class="submenu">
                    </ul>
                </li>
            @endif

            @if(Auth::user())
                <li>
                    <a href="{{ route("notes_overview") }}">Mes notes</a>
                    <ul class="submenu">
                    </ul>
                </li>
            @endif

            @if(Auth::user())
                <li>
                    <a href="{{ route("task_overview") }}">Mes tâches</a>
                    <ul class="submenu">
                    </ul>
                </li>
            @endif


            @if(Auth::user())
                <li>
                    <a href="{{ route("projet_overview") }}">Mes Projets</a>
                    <ul class="submenu">
                    </ul>
                </li>
            @endif

            @if(\Illuminate\Support\Facades\Auth::user())
                <li>
                    <a href="{{route("categorie_overview")}}">Mes categories</a>
                </li>
            @endif


            @if(Auth::user())
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Se déconnecter
                    </a></li>
            @else
                <li><a href="{{route("login")}}">
                        Se connecter
                    </a></li>
                <li><a href="{{route("register")}}">
                        S'enregister !
                    </a></li>

            @endif
        </ul>
    </nav>
</div>
<!-- Formulaire caché pour effectuer la déconnexion via une requête POST -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf <!-- Utilisation du jeton CSRF pour la sécurité -->
</form>

@if(\Illuminate\Support\Facades\Auth::user())
    @include("includes.search.searchbar")
@endif
