@include("includes.header")
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Vue Global Note - Zeus</title>
        <link rel="stylesheet" href="{{ asset("css/note/Overview.css") }}" />
        <script src="https://d3js.org/d3.v7.min.js"></script>
    </head>
    <body>
        <h1 class="center">
            Arbre de notes de
            {{ \Illuminate\Support\Facades\Auth::user()->name }}
        </h1>

        @if (count($directoryContent) != 0)
            <h2 class="center">Graphique vue d'ensemble</h2>
            <!-- Ajouter un conteneur pour le graphique -->
            <div id="graph-container"></div>
        @else
            <h2 class="center">
                Vous n'avez pas encore crée de dossier / notes, commencez par en
                créer pour voir le graphique
            </h2>
        @endif

        @if (false)
            <h2 class="center">Arborescence</h2>
            <div class="file-explorer">
                @include("arbo.folder", ["contents" => $directoryContent])
            </div>

            <script>
                let folders = document.querySelectorAll('.folder');

                folders.forEach((f) => {
                    f.addEventListener('click', () => {
                        let nestedList = f.nextElementSibling;
                        if (nestedList) {
                            nestedList.classList.toggle('active');
                        }
                    });
                });
            </script>
        @endif

        @if (count($directoryContent) != 0)
            <script type="module">
                // Importer la fonction createGraph depuis le fichier graph.js

                // Données de test (remplacer par vos données réelles)
                import { createGraph } from '../../../js/graphD3NF.js';

                const data = {!! json_encode($directoryContent) !!};

                // Créer le graphique D3 Force
                const graph = createGraph(data);

                // Ajouter le graphique au conteneur dans la page HTML
                document.getElementById('graph-container').appendChild(graph);
            </script>
        @endif
    </body>

    <style>
        .center {
            text-align: center;
            color: white;
        }

        .nodes-inst {
            font-weight: bold;
        }

        body {
            background: url({{ asset("img/galaxy_background.png") }}) no-repeat
                center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        #graph-container {
            z-index: -5;
        }
    </style>
</html>
@include("includes.footer")
