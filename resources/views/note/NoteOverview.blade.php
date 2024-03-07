@include("includes.header")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Explorer</title>
    <link rel="stylesheet" href="{{asset("css/note/Overview.css")}}">
    <script src="https://d3js.org/d3.v7.min.js"></script>


</head>
<body>

<h1 class="center">Arbre de notes de {{\Illuminate\Support\Facades\Auth::user()->name}}</h1>

<h2 class="center">Graphique vue d'ensemble</h2>
<!-- Ajouter un conteneur pour le graphique -->
<div id="graph-container"></div>


<h2 class="center">Arborescence</h2>
<div class=" file-explorer">
    @include('arbo.folder', ['contents' => $directoryContent])
</div>

<script>
    let folders = document.querySelectorAll(".folder");

    folders.forEach(f => {
        f.addEventListener("click", () => {
            let nestedList = f.nextElementSibling;
            if (nestedList) {
                nestedList.classList.toggle("active");
            }
        });
    });
</script>



<script type="module">
    // Importer la fonction createGraph depuis le fichier graph.js

    // Données de test (remplacer par vos données réelles)
    import {createGraph} from "../../../js/graphD3NF.js";

    const data = {!! json_encode($directoryContent) !!};



    // Créer le graphique D3 Force
    const graph = createGraph(data);

    // Ajouter le graphique au conteneur dans la page HTML
    document.getElementById('graph-container').appendChild(graph);
</script>



</body>

<style>
    .center {
        text-align: center;
    }
</style>
</html>
@include("includes.footer")
