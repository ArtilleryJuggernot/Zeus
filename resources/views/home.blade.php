<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editeur de Note</title>


</head>

@include("includes.header")
<body>

@if(session("failure"))
    <h2>{{session("failure")}}</h2>
@endif

<h1>Hello {{\Illuminate\Support\Facades\Auth::user()->name}}</h1>



<p>Bienvenue sur l'accueil faites <span class="bold">CTRL + P</span> pour accéder au menu rapide des <span class="bold">ressources</span></p>

<p>Votre identifiant est <strong>{{\Illuminate\Support\Facades\Auth::user()->id}}</strong>, vous pouvez le partagez à d'autre utilisateur pour qu'il autorise l'accès à leurs notes, dossiers, tâches et projets</p>

</body>
</html>
