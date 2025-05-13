@include("includes.header")

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>À propos de Zeus Project</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: pop 0.7s cubic-bezier(.4,0,.2,1) both; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-pink-50 to-yellow-50 min-h-screen flex flex-col items-center justify-center py-8">
    <div class="w-full max-w-4xl mx-auto px-4">
        <div class="mb-10 text-center animate-pop">
            <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 drop-shadow-lg mb-4">Zeus Project</h1>
            <p class="text-xl text-gray-700 font-semibold mb-2">Le second cerveau moderne pour l'organisation, la gestion de projets et la prise de notes intelligente.</p>
            <a href="https://github.com/ArtilleryJuggernot/Zeus/" target="_blank" class="inline-block mt-2 px-6 py-2 bg-gradient-to-r from-blue-500 to-pink-500 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition">Voir sur Github</a>
        </div>

        <!-- Section Présentation -->
        <div class="bg-white/90 rounded-3xl shadow-2xl p-8 mb-10 animate-pop flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-4 text-blue-600">Pourquoi Zeus ?</h2>
                <p class="text-gray-700 text-lg mb-4">Zeus est une plateforme SaaS tout-en-un pensée pour les étudiants, professionnels, créatifs et équipes qui veulent organiser leurs idées, projets, notes et ressources de façon visuelle, collaborative et sécurisée.</p>
                <ul class="list-disc list-inside text-gray-700 text-base space-y-1">
                    <li>Gestion de projets et tâches avancée</li>
                    <li>Prise de notes markdown avec éditeur immersif</li>
                    <li>Graphes interactifs pour visualiser vos liens et dossiers (inspiré d'Obsidian)</li>
                    <li>Partage et collaboration en temps réel</li>
                    <li>Catégorisation, tags, recherche intelligente</li>
                    <li>Sécurité et chiffrement des données</li>
                    <li>Interface moderne, responsive et agréable</li>
                </ul>
            </div>
            <div class="flex-1 flex justify-center">
                <img src="{{ asset('img/screens/zeus_dashboard.png') }}" alt="Dashboard Zeus" class="rounded-2xl shadow-lg w-full max-w-xs md:max-w-sm border-4 border-blue-200 bg-white/80" onerror="this.style.display='none'">
            </div>
        </div>

        <!-- Section Use Cases -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 animate-pop flex flex-col items-center">
                <img src="{{ asset('img/screens/zeus_graph.png') }}" alt="Graphe de notes" class="rounded-xl shadow w-full max-w-xs mb-4 border-2 border-pink-200 bg-white/80" onerror="this.style.display='none'">
                <h3 class="text-xl font-bold text-pink-600 mb-2">Visualisation graphique</h3>
                <p class="text-gray-700 text-center">Naviguez dans vos notes et dossiers comme dans Obsidian, avec un graphe interactif, zoom, pan, et sélection fluide.</p>
            </div>
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 animate-pop flex flex-col items-center">
                <img src="{{ asset('img/screens/zeus_editor.png') }}" alt="Éditeur de notes" class="rounded-xl shadow w-full max-w-xs mb-4 border-2 border-yellow-200 bg-white/80" onerror="this.style.display='none'">
                <h3 class="text-xl font-bold text-yellow-600 mb-2">Éditeur markdown immersif</h3>
                <p class="text-gray-700 text-center">Éditez vos notes en markdown avec StackEdit, sauvegarde automatique, export PDF, et gestion des droits d'accès.</p>
            </div>
        </div>

        <!-- Section Fonctionnalités -->
        <div class="bg-white/90 rounded-3xl shadow-2xl p-8 mb-10 animate-pop">
            <h2 class="text-2xl font-bold mb-4 text-blue-600">Fonctionnalités principales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <ul class="list-disc list-inside text-gray-700 text-base space-y-1">
                    <li>Création et organisation de dossiers, notes, tâches, projets</li>
                    <li>Gestion des catégories, tags, couleurs personnalisées</li>
                    <li>Partage de ressources avec gestion fine des droits</li>
                    <li>Recherche rapide et filtrage intelligent</li>
                </ul>
                <ul class="list-disc list-inside text-gray-700 text-base space-y-1">
                    <li>Visualisation graphique dynamique</li>
                    <li>Notifications, rappels, intégration Google Calendar</li>
                    <li>Interface mobile-friendly et dark mode</li>
                    <li>API et intégrations à venir</li>
                </ul>
            </div>
        </div>

        <!-- Section Illustration supplémentaire -->
        <div class="flex flex-col md:flex-row gap-8 mb-10 items-center animate-pop">
            <div class="flex-1 flex justify-center">
                <img src="{{ asset('img/screens/zeus_graph.png') }}" alt="Zeus mobile" class="rounded-2xl shadow-lg w-full max-w-xs md:max-w-sm border-4 border-yellow-200 bg-white/80" onerror="this.style.display='none'">
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-yellow-600 mb-2">Accessible partout</h3>
                <p class="text-gray-700 text-lg">Zeus est pensé pour être utilisé sur ordinateur, tablette et mobile. Retrouvez vos notes, projets et idées où que vous soyez, en toute sécurité.</p>
            </div>
        </div>

        <!-- Fiche auteur -->
        <div class="bg-white/90 rounded-3xl shadow-2xl p-8 mt-12 flex flex-col md:flex-row items-center gap-8 animate-pop">
            <div class="flex-shrink-0 flex flex-col items-center">
                <img src="{{ asset('img/hugo.png') }}" alt="Hugo JACQUEL" class="w-32 h-32 rounded-full border-4 border-blue-400 shadow-lg mb-2 object-cover" onerror="this.style.display='none'">
                <a href="https://www.linkedin.com/in/hugo-jacquel/" target="_blank" class="mt-2 px-4 py-1 bg-blue-600 text-white rounded-full font-bold shadow hover:bg-blue-800 transition">LinkedIn</a>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-blue-700 mb-2">Hugo Jacquel</h3>
                <p class="text-gray-700 text-lg mb-2">Développeur fullstack passionné, créateur de Zeus Project.</p>
                <p class="text-gray-600">J'aime concevoir des outils qui rendent l'organisation, la créativité et la collaboration plus simples et plus agréables. Mon objectif avec Zeus : offrir une expérience moderne, puissante et accessible à tous pour gérer ses idées, ses projets et ses connaissances.</p>
            </div>
        </div>
    </div>
</body>
</html>

@include("includes.footer")
