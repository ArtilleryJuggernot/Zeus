<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des dossiers</title>
    <link rel="stylesheet" href="{{asset("css/search/searchbar.css")}}"> <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>
<body>
</body>
</html>

<base href="
@php
echo url('/');
@endphp
" />
<div class="overlay" id="container">
    <h2 id="searchtitle">Que voulez-vous chercher ?</h2>
    <textarea id="search" placeholder="Recherche..."></textarea>
    <div id="resultblock">

    </div>
</div>



<script>
    document.getElementById('search').addEventListener('input', () => doSearch() );


    function doSearch(){
        document.getElementById("resultblock").innerHTML = "";
        // Récupération de la query
        var query = document.getElementById("search").value;

        // Envoie de la query
        fetch('/do_search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Si vous utilisez le jeton CSRF
            },
            body: JSON.stringify({
                query: query,
            })
        })
            .then(async response => {
                const jsonString = await response.json();
                var data = JSON.parse(jsonString)
                let div = document.getElementById("resultblock");
                document.getElementById("resultblock").innerHTML = "";
                data.forEach((elem) => {
                    elem = JSON.parse(elem)
                    var child = document.createElement("a");
                    child.classList.add("search")
                    if(elem["type"] == "folder"){
                        child.href = "/view_folder/" + elem["id"]
                        child.innerHTML = "<h3>"+ "[D] " + elem["name"] + "</h3>";
                        div.appendChild(child)
                    }
                    if(elem["type"] == "note"){
                        child.href = "/note_view/" + elem["id"]
                        child.innerHTML = "<h3>" + "[F] " + elem["name"] + "</h3>";
                    }
                    if(elem["type"] == "task"){
                        child.href = "/view_task/" + elem["id"]
                        child.innerHTML = "<h3>" + "[T] " + elem["name"] + "</h3>"
                    }
                    if(elem["type"] == "project"){
                        child.href = "/projet_view/" + elem["id"]
                        child.innerHTML = "<h3>" + "[P] " + elem["name"] + "</h3>"
                    }

                    child.classList.add(elem["type"]);
                    div.appendChild(child)
                    div.innerHTML += "<br>"
                })
            })

    }

    let status = 0; // Pas visible
    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 'p') {
            // Prevent the Save dialog to open
            e.preventDefault();
            if(status)
                hideOverlay();
            else
                showOverlay();
        }
    });

    function showOverlay() {
        document.getElementById('container').style.display = 'block';
        status = 1;
        document.getElementById("search").focus();
        document.getElementById("search").click();
    }

    // Pour masquer l'overlay
    function hideOverlay() {
        document.getElementById('container').style.display = 'none';
        status = 0;
    }
</script>
