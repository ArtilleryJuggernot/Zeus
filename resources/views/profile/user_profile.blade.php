@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des projets</title>
    <link rel="stylesheet" href="{{asset("css/folder/Overview.css")}}">

    <style>
.form{
    display: flex;
    flex-wrap: wrap;
}
    </style>
</head>

<body>

<h1>Page de l'utilisateur {{$user->name}}</h1>


<h2>Modification du mot de passe</h2>

<div class="form">
<form action="{{route("update_password")}}" method="POST">

    <label>Entez votre mot de passe actuel : </label>
    <br>
    <input name="oldpassword" type="password" required placeholder="Ancien mot de passe">
    <br>
    <label>Entrez votre nouveau mot de passe :</label>
    <br>
    <input name="newpassword" type="password" required placeholder="Nouveau mot de passe">
    <br>
    <label>Confirmation :</label>
    <br>
    <input name="confirmation" type="password" required placeholder="Confirmation">
    <br>
    <button type="submit">Changer mon mot de passe</button>
    @csrf
</form>
</div>

</body>

@include("includes.footer")
