@include("includes.header")
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Vue Globale des Notes - Zeus</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://d3js.org/d3.v7.min.js"></script>
        <style>
            body {
                background: linear-gradient(135deg, #1e293b 0%, #6366f1 40%, #ec4899 100%) fixed;
                min-height: 100vh;
            }
            .graph-card {
                background: rgba(255,255,255,0.85);
                border-radius: 2rem;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
                backdrop-filter: blur(6px);
                -webkit-backdrop-filter: blur(6px);
                border: 1px solid rgba(255,255,255,0.18);
            }
            .node-glow {
                filter: drop-shadow(0 0 12px #6366f1) drop-shadow(0 0 24px #ec4899);
            }
            .node-selected {
                filter: drop-shadow(0 0 24px #facc15) brightness(1.2);
            }
            .graph-controls {
                position: absolute;
                top: 1.5rem;
                right: 2rem;
                z-index: 10;
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto relative">
            <div class="mb-8 text-center animate-pop">
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-pink-400 to-yellow-300 drop-shadow-lg mb-2">Vue Graphique des Notes</h1>
                <p class="text-lg text-white/80">Explorez vos notes et dossiers comme dans Obsidian !</p>
            </div>
            <div class="graph-controls flex gap-2">
                <button id="zoom-in" class="bg-gradient-to-r from-blue-500 to-pink-500 text-white font-bold py-2 px-4 rounded-xl shadow-lg hover:scale-110 transition">+</button>
                <button id="zoom-out" class="bg-gradient-to-r from-pink-500 to-yellow-400 text-white font-bold py-2 px-4 rounded-xl shadow-lg hover:scale-110 transition">-</button>
                <button id="center-graph" class="bg-gradient-to-r from-yellow-400 to-blue-500 text-white font-bold py-2 px-4 rounded-xl shadow-lg hover:scale-110 transition">Recentrer</button>
            </div>
            <div class="graph-card mx-auto p-4 md:p-8 w-full min-h-[600px] flex flex-col items-center justify-center relative">
                <div id="graph-container" class="w-full h-[600px] md:h-[700px] relative"></div>
                <div id="selected-node" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/90 px-6 py-2 rounded-full shadow text-gray-800 font-bold text-lg hidden"></div>
            </div>
        </div>
        <script type="module">
            import { createGraph } from '../../../js/graphD3NF.js';
            const data = {!! json_encode($directoryContent) !!};
            // Créer le graphique D3 Force
            const graph = createGraph(data, {
                container: document.getElementById('graph-container'),
                onNodeSelect: (name) => {
                    const nodeBox = document.getElementById('selected-node');
                    nodeBox.textContent = name;
                    nodeBox.classList.remove('hidden');
                    setTimeout(() => nodeBox.classList.add('hidden'), 2000);
                },
                controls: {
                    zoomIn: document.getElementById('zoom-in'),
                    zoomOut: document.getElementById('zoom-out'),
                    center: document.getElementById('center-graph')
                }
            });
            document.getElementById('graph-container').appendChild(graph);
        </script>
    </body>
</html>
@include("includes.footer")
