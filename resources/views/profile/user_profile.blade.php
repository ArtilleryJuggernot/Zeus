@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Page de {{ $user->name }} - Zeus</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <link rel="stylesheet" href="{{asset("css/profil/profil.css")}}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
    <style>
        .form {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</head>

<body class="bg-gray-100">

<h1 class="text-3xl font-bold mb-4">Page de l'utilisateur {{ $user->name }} ⚡</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Il y a eu des erreurs</h2>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="flex pb-16">
    <div class="relative pr-60">
        <img class="max-w-150 max-h-150 absolute" src="{{asset("overlay/overlay_steam_vampire.png")}}" style="left: 10px; z-index: 2;">
        @if($user->pfp_path != '')
            @if(\Illuminate\Support\Facades\Storage::has('app/profile_picture/') . $user->id . ".png")
                <img class="pfp_profil_page absolute" src="{{asset("storage/" . $user->id . ".png")}}" style="left: 10px;">

            @else
                <img class="pfp_profil_page absolute" src="{{asset("storage/default.png")}}" />
            @endif

        @else
            <img class="pfp_profil_page absolute" src="{{asset("storage/default.png")}}" />
        @endif
    </div>

    <div class="relative">
        <h2 class="font-bold">Information</h2>
        <p>Nom d'utilisateur : {{ $user->name }}</p>
        <p>Date de création du compte : {{ $user->created_at }}</p>
    </div>
</div>

<br />
<br />

<form class="pt-20" id="profileForm" action="/upload-profile-picture" method="post" enctype="multipart/form-data" class="mb-8">
    <input type="file" name="profilePicture" id="profilePicture" class="mb-2">
    <button type="submit" class="bg-blue-500 text-white font-bold px-4 py-2 rounded cursor-pointer hover:bg-blue-600">Upload</button>
    @csrf
</form>

@php
    if ($stats["total_tasks"] != 0) {
    $pourcent_tt = round(($stats["completed_tasks_total"] / $stats["total_tasks"]) * 100, 3) . "%";
    }

    if ($stats["total_tasks_no_project"] != 0) {
    $pourcent_thp = round(($stats["completed_tasks_no_project"] / $stats["total_tasks_no_project"]) * 100, 3) . "%";
    }

    if ($stats["total_tasks_project"] != 0) {
    $pourcent_tp = round(($stats["completed_tasks_project"] / $stats["total_tasks_project"]) * 100, 3) . "%";
    }
@endphp

<div class="container">
    <h2 class="font-bold">Statistiques</h2>
    <div class="stats">
        <p><strong>➡️ Nombre total de notes :</strong> {{ $stats["total_notes"] }}</p>
        <p><strong>➡️ Nombre total de dossiers :</strong> {{ $stats["total_folders"] }}</p>
        <p><strong>➡️ Nombre total de projets :</strong> {{ $stats["total_projects"] }}</p>
        <p>
            <strong>➡️ Nombre de tâches réalisées (total) :</strong>
            @if ($stats["total_tasks"] != 0)
                {{ $stats["completed_tasks_total"] }} / {{ $stats["total_tasks"] }} ({{ $pourcent_tt }})
            @else
                Pas de tâches encore crées !
            @endif
        </p>
        <p>
            <strong>➡️ Nombre de tâches réalisées (hors projet) :</strong>
            @if ($stats["total_tasks_no_project"] != 0)
                {{ $stats["completed_tasks_no_project"] }} / {{ $stats["total_tasks_no_project"] }} ({{ $pourcent_thp }})
            @else
                Pas de tâches hors projet encore crées !
            @endif
        </p>
        <p>
            <strong>➡️ Nombre de tâches réalisées (projet) :</strong>
            @if ($stats["total_tasks_project"] != 0)
                {{ $stats["completed_tasks_project"] }} / {{ $stats["total_tasks_project"] }} ({{ $pourcent_tp }})
            @else
                Pas de tâches dans les projets encore crées !
            @endif
        </p>
        <p><strong>➡️ Nombre total de catégories :</strong> {{ $stats["total_categories"] }}</p>
    </div>
</div>


@if($user->id == \Illuminate\Support\Facades\Auth::user()->id)
<h2 class="font-bold text-xl pt-5 pb-5">Modification du mot de passe</h2>

<div class="form">
    <form action="{{ route("update_password") }}" method="POST" class="flex-col">
        <div>
            <label class="mb-2">Entez votre mot de passe actuel :</label>
            <input name="oldpassword" type="password" required placeholder="Ancien mot de passe" class="mb-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500">
        </div>

        <div>
            <label class="mb-2">Entrez votre nouveau mot de passe :</label>
            <input name="newpassword" type="password" required placeholder="Nouveau mot de passe" class="mb-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500">
        </div>

        <div>
            <label class="mb-2">Confirmation :</label>
            <input name="confirmation" type="password" required placeholder="Confirmation" class="mb-2 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500">
        </div>

        <button type="submit" class="bg-blue-500 text-white font-bold px-4 py-2 rounded cursor-pointer hover:bg-blue-600">Changer mon mot de passe</button>
        @csrf
    </form>
</div>
@endif

@include("includes.footer")

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
