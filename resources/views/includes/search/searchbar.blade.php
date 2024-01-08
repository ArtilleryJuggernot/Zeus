<base href="http://127.0.0.1:8000/" />
<div class="overlay" id="container">
    <h2 id="searchtitle">Que voulez-vous chercher ?</h2>
    <textarea id="search" placeholder="Recherche..."></textarea>
    <div id="resultblock">

    </div>
</div>

<style scoped>

    *{
        overflow-x: auto;
        overflow-y: auto;
    }

    #searchtitle{
        text-align: center;
        color: white;
        font-size: 30px;
    }

    .overlay {
        position: fixed; /* Position fixée pour rester en place lors du défilement */
        top: 0;
        left: 0;
        width: 100%; /* Prend toute la largeur de la fenêtre */
        height: 100%; /* Prend toute la hauteur de la fenêtre */
        background-color: rgba(0, 0, 0, 0.7); /* Fond noir semi-transparent */
        z-index: 999; /* Assure que l'overlay est au-dessus du contenu */
        display: none; /* Masque l'overlay par défaut */
    }

    /* Styliser le contenu de l'overlay */
    .overlay h3 {
        color: white;
    }

    .overlay textarea {
        width: 80%;
        height: 200px;
        margin: 20px auto;
        display: block;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        background-color: white; /* Fond blanc pour le textarea */
    }
    #resultblock{
        display: flex;
        flex-wrap: wrap;
    }
    #resultblock > a {
      margin: 10px;
        flex: 1 0 21%; /* explanation below */
    }

    #resultblock > .folder{
        background-color:rgba(55, 55, 246, 0.5);
    }

    #resultblock > .note{
        background-color:rgba(227, 55, 55, 0.5)
    }

    #resultblock > .task{
        background-color: rgba(6, 255, 0, 0.5)
    }

    #resultblock > .project{
        background-color: rgba(255, 232, 0, 0.5)
    }

</style>

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
