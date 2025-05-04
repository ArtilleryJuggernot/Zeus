@include("includes.header")

    <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Profil de {{ $user->name }} - Zeus</title>
    <link rel="stylesheet" href="{{ asset('css/notification/notification.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss-animate@1.0.0/dist/index.min.js"></script>
    <style>
    @keyframes gradient-move {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .animate-gradient-move {
      animation: gradient-move 6s ease-in-out infinite;
    }
    @keyframes bg-move-pro {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .bg-animated-pro {
      background: linear-gradient(120deg, #181c2b 0%, #232946 50%, #3a506b 100%);
      background-size: 200% 200%;
      animation: bg-move-pro 18s ease-in-out infinite;
    }
    .stars-card {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      pointer-events: none;
      z-index: 1;

    }
    .card-content {
      position: relative;
      z-index: 2;
    }
    </style>
</head>

<body class="bg-animated-pro min-h-screen relative overflow-x-hidden">
    <svg class="stars-card" width="100%" height="100%">
        <circle cx="10%" cy="20%" r="1.5" fill="white" opacity="0.8">
            <animate attributeName="cy" values="20%;110%;20%" dur="8s" repeatCount="indefinite" />
        </circle>
        <circle cx="30%" cy="80%" r="1" fill="white" opacity="0.6">
            <animate attributeName="cy" values="80%;-10%;80%" dur="12s" repeatCount="indefinite" />
        </circle>
        <circle cx="70%" cy="40%" r="1.2" fill="white" opacity="0.7">
            <animate attributeName="cy" values="40%;120%;40%" dur="10s" repeatCount="indefinite" />
        </circle>
        <circle cx="90%" cy="10%" r="1.7" fill="white" opacity="0.9">
            <animate attributeName="cy" values="10%;100%;10%" dur="14s" repeatCount="indefinite" />
        </circle>
        <circle cx="50%" cy="60%" r="1.3" fill="white" opacity="0.5">
            <animate attributeName="cy" values="60%;-10%;60%" dur="11s" repeatCount="indefinite" />
        </circle>
        <circle cx="80%" cy="30%" r="1.1" fill="white" opacity="0.7">
            <animate attributeName="cy" values="30%;110%;30%" dur="13s" repeatCount="indefinite" />
        </circle>
        <circle cx="20%" cy="70%" r="1.4" fill="white" opacity="0.6">
            <animate attributeName="cy" values="70%;-10%;70%" dur="9s" repeatCount="indefinite" />
        </circle>
        <circle cx="60%" cy="15%" r="1.2" fill="white" opacity="0.8">
            <animate attributeName="cy" values="15%;100%;15%" dur="15s" repeatCount="indefinite" />
        </circle>
    </svg>

<div class="max-w-4xl mx-auto mt-10 p-8 rounded-3xl shadow-2xl bg-white/10 backdrop-blur-lg border border-blue-400/30 relative overflow-hidden">
    <!-- SVG décoratif -->
    <svg class="absolute -top-10 -left-10 w-48 h-48 opacity-20 pointer-events-none" viewBox="0 0 200 200">
        <defs>
            <radialGradient id="grad1" cx="50%" cy="50%" r="50%">
                <stop offset="0%" style="stop-color:rgb(59,130,246);stop-opacity:1" />
                <stop offset="100%" style="stop-color:rgb(30,41,59);stop-opacity:0" />
            </radialGradient>
        </defs>
        <circle cx="100" cy="100" r="100" fill="url(#grad1)" />
    </svg>

    <div class="flex flex-col md:flex-row items-center gap-8 z-10 relative">
        <!-- Avatar -->
        <div class="flex flex-col items-center">
            @php
                $profilePath = 'storage/' . $user->id . '.png';
                $hasProfilePic = Storage::exists($profilePath);
                $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(mb_substr($w,0,1)))->join('');
                $initials = mb_substr($initials, 0, 2);
            @endphp
            @if($hasProfilePic)
                <img class="w-40 h-40 rounded-full border-4 border-blue-400 shadow-lg object-cover animate-fade-in" src="{{ asset('storage/' . $user->id . '.png') }}" alt="Photo de profil">
            @else
                <div class="w-40 h-40 rounded-full bg-blue-500 flex items-center justify-center text-white text-6xl font-bold border-4 border-blue-400 shadow-lg animate-fade-in select-none">
                    {{ $initials }}
                </div>
            @endif
            <form action="/upload-profile-picture" method="post" enctype="multipart/form-data" class="mt-4 flex flex-col items-center">
                <input type="file" name="profilePicture" id="profilePicture" class="mb-2 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                <button type="submit" class="bg-blue-500 text-white font-bold px-4 py-2 rounded-full shadow hover:bg-blue-600 transition">Changer l'avatar</button>
                @csrf
            </form>
        </div>
        <!-- Infos utilisateur -->
        <div class="flex-1">
            <h1 class="text-4xl font-extrabold flex items-center gap-3 mb-2 animate-fade-in bg-gradient-to-r from-blue-400 via-pink-400 to-yellow-300 bg-[length:200%_200%] bg-clip-text text-transparent animate-gradient-move leading-[1.2] pb-1">
                {{ $user->name }}
                <svg class="w-8 h-8 text-yellow-400 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </h1>
            <p class="text-blue-200 text-lg mb-2">Membre depuis le {{ $user->created_at->format('d/m/Y') }}</p>
            <div class="flex flex-wrap gap-2">
                <span class="bg-blue-700/80 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">ID: {{ $user->id }}</span>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="mt-10">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 3v18h18" />
            </svg>
            Statistiques
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 shadow-lg flex flex-col items-center animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white mb-2">
  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
</svg>

                <span class="text-white text-lg font-bold">Notes</span>
                <span class="text-2xl text-white font-extrabold">{{ $stats['total_notes'] }}</span>
            </div>
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl p-6 shadow-lg flex flex-col items-center animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white mb-2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
</svg>

                <span class="text-white text-lg font-bold">Dossiers</span>
                <span class="text-2xl text-white font-extrabold">{{ $stats['total_folders'] }}</span>
            </div>
            <div class="bg-gradient-to-br from-pink-500 to-pink-700 rounded-2xl p-6 shadow-lg flex flex-col items-center animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white mb-2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
</svg>

                <span class="text-white text-lg font-bold">Projets</span>
                <span class="text-2xl text-white font-extrabold">{{ $stats['total_projects'] }}</span>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 shadow-lg flex flex-col items-center animate-fade-in">
                <svg class="w-10 h-10 text-white mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-white text-lg font-bold">Tâches réalisées</span>
                <span class="text-2xl text-white font-extrabold">{{ $stats['completed_tasks_total'] }}</span>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 shadow-lg flex flex-col items-center animate-fade-in">
            <svg class="w-10 h-10 text-white mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-white text-lg font-bold">Tâches hors projet</span>
                <span class="text-2xl text-white font-extrabold">{{ $stats['completed_tasks_no_project'] }}</span>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-6 shadow-lg flex flex-col items-center animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white mb-2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
</svg>

                <span class="text-white text-lg font-bold">Catégories</span>
                <span class="text-2xl text-white font-extrabold">{{ $stats['total_categories'] }}</span>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('profilePicture');

        profilePictureInput.addEventListener('change', function() {
            document.getElementById('profileForm').submit(); // Soumet automatiquement le formulaire lorsque l'utilisateur choisit une image
        });
    });
</script>

<script>
    @if(session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif(session("failure"))
    showNotification("{{ session("success") }}", 'failure');
    @endif
</script>

</body>

</html>
