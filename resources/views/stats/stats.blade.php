@if (! $render)
    @include("includes.header")
@endif

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Weekly Statistics</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset("js/graph.js") }}"></script>

        @if ($render)
            <link
                rel="stylesheet"
                href="{{ base_path("public/css/pdf/pdf.css") }}"
            />
        @else
            <link rel="stylesheet" href="{{ asset("css/pdf/pdf.css") }}" />
        @endif
    </head>
    <body>
        <h1 class="text-3xl font-bold pt-5 pb-5" style="text-align: center">
            Rapport statistiques du
            {{ $monday->locale("fr")->translatedFormat("l d F Y") }} au
            {{ $sunday->locale("fr")->translatedFormat("l d F Y") }}
        </h1>

        <div class="table-account center">
            <table border="1">
                <thead>
                    <tr>
                        <th style="text-align: center" colspan="2">
                            Informations générales
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: middle; text-align: left">
                            <div class="center">
                                <img
                                    id="zeus-logo"
                                    src="{{ asset("img/logo-zeus.jpeg") }}"
                                    alt="Zeus Logo"
                                />
                            </div>
                        </td>
                        <td style="text-align: left; padding: 10px">
                            <table>
                                <tr>
                                    <td>Date d'aujourd'hui:</td>
                                    <td>{{ now()->format("d/m/Y") }}</td>
                                </tr>
                                <tr>
                                    <td>Nom d'utilisateur:</td>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td>Date de création du compte:</td>
                                    <td>
                                        {{ Auth::user()->created_at->format("d/m/Y") }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Version de l'application Zeus:</td>
                                    <td>{{ env("APP_VERSION", "N/A") }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br />
        <hr />

        <h2 class="text-2xl font-bold pt-5 pb-5" style="text-align: center">Entre cette période :</h2>

        <div class="  flex justify-center">
            <div class="mr-5 ml-5 card notes">
                <div class="emoji">
                    {{ $stats["notesCreated"]->count() > 0 ? "📈" : "⏸️" }}
                </div>
                <div class="info">
                    <div class="title">Nouvelles notes créées 📝</div>
                    <div class="count">
                        {{ $stats["notesCreated"]->count() }}
                    </div>
                </div>
            </div>

            <div class="mr-5 ml-5 card folders">
                <div class="emoji">
                    {{ $stats["foldersCreated"]->count() > 0 ? "📈" : "⏸️" }}
                </div>
                <div class="info">
                    <div class="title">Nouveaux dossiers créés 📁</div>
                    <div class="count">
                        {{ $stats["foldersCreated"]->count() }}
                    </div>
                </div>
            </div>

            <div class="mr-5 ml-5 card tasks">
                <div class="emoji">
                    {{ $stats["tasksCreated"]->count() > 0 ? "📈" : "⏸️" }}
                </div>
                <div class="info">
                    <div class="title">Nouvelles tâches créées 📚</div>
                    <div class="count">
                        {{ $stats["tasksCreated"]->count() }}
                    </div>
                </div>
            </div>

            <div class="mr-5 ml-5 card projects">
                <div class="emoji">
                    {{ $stats["projectsCreated"]->count() > 0 ? "📈" : "⏸️" }}
                </div>
                <div class="info">
                    <div class="title">Nouveaux projets créés 🚧</div>
                    <div class="count">
                        {{ $stats["projectsCreated"]->count() }}
                    </div>
                </div>
            </div>

            <div class="mr-5 ml-5 card categories">
                <div class="emoji">
                    {{ $stats["categoriesCreated"]->count() > 0 ? "📈" : "⏸️" }}
                </div>
                <div class="info">
                    <div class="title">Nouvelles catégories créées 📌</div>
                    <div class="count">
                        {{ $stats["categoriesCreated"]->count() }}
                    </div>
                </div>
            </div>
        </div>

        <br />
        <hr />

        <h2 class="text-2xl font-bold pt-5 pb-5" style="text-align: center">Graphique de la semaine :</h2>

        <canvas id="ChartWeekly"></canvas>
        <br />
        <hr />

        <h2 class="text-2xl font-bold pt-5 pb-5" style="text-align: center">Statistiques générales</h2>

        @php
            if ($statsOverall["total_tasks"] != 0) {
                $pourcent_tt = round(($statsOverall["completed_tasks_total"] / $statsOverall["total_tasks"]) * 100, 3) . "%";
            }

            if ($statsOverall["total_tasks_no_project"] != 0) {
                $pourcent_thp = round(($statsOverall["completed_tasks_no_project"] / $statsOverall["total_tasks_no_project"]) * 100, 3) . "%";
            }

            if ($statsOverall["total_tasks_project"] != 0) {
                $pourcent_tp = round(($statsOverall["completed_tasks_project"] / $statsOverall["total_tasks_project"]) * 100, 3) . "%";
            }
        @endphp

        <div class="flex justify-center pb-10">
            <div class=" mr-5 ml-5 card notes">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">Nombre total de notes 📝</div>
                    <div class="count">{{ $statsOverall["total_notes"] }}</div>
                </div>
            </div>

            <div class=" mr-5 ml-5 card folders">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">Nombre total de dossiers 📁</div>
                    <div class="count">
                        {{ $statsOverall["total_folders"] }}
                    </div>
                </div>
            </div>

            <div class=" mr-5 ml-5 card projects">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">Nombre total de projets 🚧</div>
                    <div class="count">
                        {{ $statsOverall["total_projects"] }}
                    </div>
                </div>
            </div>

            <div class="mr-5 ml-5  card tasks">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">
                        Nombre de tâches réalisées (total) 📚
                    </div>
                    <div class="count">
                        @if ($statsOverall["total_tasks"] != 0)
                            {{ $statsOverall["completed_tasks_total"] }} /
                            {{ $statsOverall["total_tasks"] }}
                            ({{ $pourcent_tt }})
                        @else
                            {{ $statsOverall["total_tasks"] }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="mr-5 ml-5  card tasks">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">
                        Nombre de tâches réalisées (hors projet) 📚
                    </div>
                    <div class="count">
                        @if ($statsOverall["total_tasks_no_project"] != 0)
                            {{ $statsOverall["completed_tasks_no_project"] }} /
                            {{ $statsOverall["total_tasks_no_project"] }}
                            ({{ $pourcent_thp }})
                        @else
                            {{ $statsOverall["total_tasks_no_project"] }}
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-center">
            <div class=" mr-5 ml-5  card tasks">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">
                        Nombre de tâches réalisées (projet) 📚
                    </div>
                    <div class="count">
                        @if ($statsOverall["total_tasks_project"] != 0)
                            {{ $statsOverall["completed_tasks_project"] }} /
                            {{ $statsOverall["total_tasks_project"] }}
                            ({{ $pourcent_tp }})
                        @else
                            {{ $statsOverall["total_tasks_project"] }}
                        @endif
                    </div>
                </div>
            </div>

            <div class=" mr-5 ml-5  card categories">
                <div class="emoji">👑</div>
                <div class="info">
                    <div class="title">Nombre total de catégories 📌</div>
                    <div class="count">
                        {{ $statsOverall["total_categories"] }}
                    </div>
                </div>
            </div>
        </div>


        <br />
        <hr />

        <h2 class="text-3xl font-bold pt-5 pb-5" style="text-align: center">Graphique global (depuis le début)</h2>



        <canvas id="ChartOverall"></canvas>

        <h2 class="text-3xl font-bold pt-5 pb-5" style="text-align: center">Diagramme Catégories</h2>
        <div style="width: 600px; height: 400px;">
            <h3 class="center-canvas" style="padding-left: 80px">Toute ressources confondu</h3>
            <canvas id="camembertChart"></canvas>
        </div>

        @php
            $datasetWeekly = \App\Http\Controllers\GraphController::StatsToGraphDataset($stats);
            $datasetOverall = \App\Http\Controllers\GraphController::StatsToGraphDataset($statsOverallGraph);
        @endphp

        <script type="module">
            import { graphSet } from '../../../js/graph.js';

            let datasetWeekly = {!! json_encode($datasetWeekly) !!};
            graphSet('ChartWeekly', datasetWeekly);

            let datasetOverall = {!! json_encode($datasetOverall) !!};
            graphSet('ChartOverall', datasetOverall);
        </script>

        <script>
            // Récupérer les données PHP dans des variables JavaScript
            const statsByCategory = @json($categorieAllStats);


            let arrayData = Object.values(Object.values(Object.values(statsByCategory)[1])[0])


            // Extraire les noms de catégorie, les nombres de ressources et les couleurs pour le diagramme camembert
            const labels = Object.values(arrayData).map(category => category.category_name);
            const data = Object.values(arrayData).map(category => category.nombre_ressources);
            const colors = Object.values(arrayData).map(category => category.color);


            // Créer le diagramme camembert avec Chart.js
            const ctx = document.getElementById('camembertChart').getContext('2d');
            const camembertChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pourcentage de ressources par catégorie',
                            data: data,
                            backgroundColor: colors.map(color => `rgba(${hexToRgb(color)}, 0.2)`),
                            borderColor: colors.map(color => `rgba(${hexToRgb(color)}, 1)`),
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            // Fonction pour convertir une couleur hexadécimale en RGB
            function hexToRgb(hex) {
                // Supprimer le caractère #
                hex = hex.replace(/^#/, '');

                // Séparer les composantes de couleur
                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);

                return `${r}, ${g}, ${b}`;
            }

        </script>
    </body>
</html>

@if (!$render)
    @include("includes.footer")
@endif
