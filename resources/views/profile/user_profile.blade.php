@include("includes.header")

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Page de {{ $user->name }} - Zeus</title>
        <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
        <link rel="stylesheet" href="{{asset("css/profil/profil.css")}}" />
        <link
            rel="stylesheet"
            href="{{ asset("css/notification/notification.css") }}"
        />
        <style>
            .form {
                display: flex;
                flex-wrap: wrap;
            }
        </style>
    </head>

    <body>
        <h1>Page de l'utilisateur {{ $user->name }} ⚡</h1>

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

<div style="display: flex">
    <div style="padding-right: 30px">
        @if($user->pfp_path != '')
            @if(\Illuminate\Support\Facades\Storage::has('app/profile_picture/') . $user->id . ".png")
                <img class="pfp_profil_page" src="{{asset("storage/" . $user->id . ".png")}}" />
            @endif

        @else
            <img class="pfp_profil_page" src="{{asset("storage/default.png")}}" />
        @endif
    </div>


    <div>
        <h2>Information</h2>

        <p>Nom d'utilisateur : {{ $user->name }}</p>

        <p>Date de création du compte : {{ $user->created_at }}</p>
    </div>


</div>




        <form id="profileForm" action="/upload-profile-picture" method="post" enctype="multipart/form-data">
            <input type="file" name="profilePicture" id="profilePicture">
            <button type="submit">Upload</button>
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
            <h2>Statistiques</h2>

            <div class="stats">
                <p>
                    <strong>➡️ Nombre total de notes :</strong>
                    {{ $stats["total_notes"] }}
                </p>
                <p>
                    <strong>➡️ Nombre total de dossiers :</strong>
                    {{ $stats["total_folders"] }}
                </p>
                <p>
                    <strong>➡️ Nombre total de projets :</strong>
                    {{ $stats["total_projects"] }}
                </p>
                <p>
                    <strong>➡️ Nombre de tâches réalisées (total) :</strong>

                    @if ($stats["total_tasks"] != 0)
                        {{ $stats["completed_tasks_total"] }} /
                        {{ $stats["total_tasks"] }} ({{ $pourcent_tt }})
                    @else
                            Pas de tâches encore crées !
                    @endif
                </p>

                <p>
                    <strong>
                        ➡️ Nombre de tâches réalisées (hors projet) :
                    </strong>

                    @if ($stats["total_tasks_no_project"] != 0)
                        {{ $stats["completed_tasks_no_project"] }} /
                        {{ $stats["total_tasks_no_project"] }}
                        ({{ $pourcent_thp }})
                    @else
                            Pas de tâches hors projet encore crées !
                    @endif
                </p>

                <p>
                    <strong>➡️ Nombre de tâches réalisées (projet) :</strong>

                    @if ($stats["total_tasks_project"] != 0)
                        {{ $stats["completed_tasks_project"] }} /
                        {{ $stats["total_tasks_project"] }}
                        ({{ $pourcent_tp }})
                    @else
                            Pas de tâches dans les projets encore crées !
                    @endif
                </p>

                <p>
                    <strong>➡️ Nombre total de catégories :</strong>
                    {{ $stats["total_categories"] }}
                </p>
            </div>
        </div>

        <h2>Modification du mot de passe</h2>

        <div class="form">
            <form action="{{ route("update_password") }}" method="POST">
                <label>Entez votre mot de passe actuel :</label>
                <br />
                <input
                    name="oldpassword"
                    type="password"
                    required
                    placeholder="Ancien mot de passe"
                />
                <br />
                <label>Entrez votre nouveau mot de passe :</label>
                <br />
                <input
                    name="newpassword"
                    type="password"
                    required
                    placeholder="Nouveau mot de passe"
                />
                <br/>
                <label>Confirmation :</label>
                <br />
                <input name="confirmation" type="password" required placeholder="Confirmation"/>
                <br />
                <button type="submit">Changer mon mot de passe</button>
                @csrf
            </form>
        </div>
    </body>

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

</html>
