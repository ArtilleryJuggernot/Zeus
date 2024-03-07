@if(!$render)
    @include("includes.header")
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset("js/graph.js")}}"></script>

    @if($render)
        <link rel="stylesheet" href="{{ base_path('public/css/pdf/pdf.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/pdf/pdf.css') }}">
    @endif


</head>
<body>



<h1 style="text-align: center">Rapport statistiques du {{$monday->locale('fr')->translatedFormat('l d F Y')}}
au {{$sunday->locale('fr')->translatedFormat('l d F Y')}}</h1>


<div class="table-account center">

    <table border="1">
        <thead>
        <tr>
            <th style="text-align: center" colspan="2">Informations générales</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="vertical-align: middle; text-align: left;">
                <div class="center">
                    <img id="zeus-logo" src="{{ asset('img/logo-zeus.jpeg') }}" alt="Zeus Logo">
                </div>

            </td>
            <td style="text-align: left; padding: 10px;">
                <table>
                    <tr>
                        <td>Date d'aujourd'hui:</td>
                        <td>{{ now()->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Nom d'utilisateur:</td>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td>Date de création du compte:</td>
                        <td>{{ Auth::user()->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Version de l'application Zeus:</td>
                        <td>{{ env('APP_VERSION', 'N/A') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

</div>
    <br>
<hr/>

<h2 style="text-align: center">Entre cette période :</h2>


    <div class="container">
        <div class="card notes">
            <div class="emoji">{{ $stats['notesCreated']->count() > 0 ? '📈' : '⏸️' }}</div>
            <div class="info">
                <div class="title">Nouvelles notes créées 📝</div>
                <div class="count">{{ $stats['notesCreated']->count() }}</div>
            </div>
        </div>

        <div class="card folders">
            <div class="emoji">{{ $stats['foldersCreated']->count() > 0 ? '📈' : '⏸️' }}</div>
            <div class="info">
                <div class="title">Nouveaux dossiers créés 📁</div>
                <div class="count">{{ $stats['foldersCreated']->count() }}</div>
            </div>
        </div>

        <div class="card tasks">
            <div class="emoji">{{ $stats['tasksCreated']->count() > 0 ? '📈' : '⏸️' }}</div>
            <div class="info">
                <div class="title">Nouvelles tâches créées 📚</div>
                <div class="count">{{ $stats['tasksCreated']->count() }}</div>
            </div>
        </div>

        <div class="card projects">
            <div class="emoji">{{ $stats['projectsCreated']->count() > 0 ? '📈' : '⏸️' }}</div>
            <div class="info">
                <div class="title">Nouveaux projets créés 🚧</div>
                <div class="count">{{ $stats['projectsCreated']->count() }}</div>
            </div>
        </div>

        <div class="card categories">
            <div class="emoji">{{ $stats['categoriesCreated']->count() > 0 ? '📈' : '⏸️' }}</div>
            <div class="info">
                <div class="title">Nouvelles catégories créées 📌</div>
                <div class="count">{{ $stats['categoriesCreated']->count() }}</div>
            </div>
        </div>
    </div>

<br>
<hr/>

<h2 style="text-align: center">Graphique de la semaine</h2>

<canvas id="ChartWeekly"></canvas>
<br>
<hr/>




<h2 style="text-align: center">Statistiques générales</h2>

@php
    if ($statsOverall['total_tasks'] != 0) $pourcent_tt = round($statsOverall['completed_tasks_total']  /  $statsOverall['total_tasks'] * 100,3) . "%";

    if ($statsOverall['total_tasks_no_project'] != 0) $pourcent_thp = round($statsOverall['completed_tasks_no_project']  /  $statsOverall['total_tasks_no_project'] * 100,3) . "%";

    if ($statsOverall['total_tasks_project'] != 0) $pourcent_tp = round($statsOverall['completed_tasks_project']  /  $statsOverall['total_tasks_project'] * 100,3) . "%";

@endphp

<div class="container">




    <div class="card notes">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre total de notes 📝</div>
            <div class="count">{{ $statsOverall['total_notes']}}</div>
        </div>
    </div>

    <div class="card folders">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre total de dossiers 📁</div>
            <div class="count">{{ $statsOverall['total_folders']}}</div>
        </div>
    </div>

    <div class="card projects">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre total de projets 🚧</div>
            <div class="count">{{ $statsOverall['total_projects']}}</div>
        </div>
    </div>


    <div class="card tasks">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre de tâches réalisées (total) 📚</div>
            <div class="count">
                @if($statsOverall['total_tasks'] != 0)
                    {{ $statsOverall['completed_tasks_total'] }} / {{ $statsOverall['total_tasks'] }} ({{$pourcent_tt}})
                @else
                    {{ $statsOverall['total_tasks'] }}
                @endif
            </div>
        </div>
    </div>


    <div class="card tasks">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre de tâches réalisées (hors projet) 📚</div>
            <div class="count">
                @if($statsOverall['total_tasks_no_project'] != 0)
                    {{ $statsOverall['completed_tasks_no_project'] }} / {{ $statsOverall['total_tasks_no_project'] }} ({{$pourcent_thp}})
                @else
                    {{$statsOverall['total_tasks_no_project']}}
                @endif
            </div>
        </div>
    </div>

    <div class="card tasks">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre de tâches réalisées (projet) 📚</div>
            <div class="count">
                @if($statsOverall['total_tasks_project'] != 0)
                    {{ $statsOverall['completed_tasks_project'] }} / {{ $statsOverall['total_tasks_project'] }} ({{$pourcent_tp}})
                @else
                    {{$statsOverall['total_tasks_project']}}
                @endif
            </div>
        </div>
    </div>


    <div class="card categories">
        <div class="emoji">👑</div>
        <div class="info">
            <div class="title"> Nombre total de catégories 📌</div>
            <div class="count">{{ $statsOverall['total_categories']}}</div>
        </div>
    </div>


</div>


<br>
<hr/>

<h2 style="text-align: center">Graphique global (depuis le début)</h2>

<canvas id="ChartOverall"></canvas>

@php

$datasetWeekly = \App\Http\Controllers\GraphController::StatsToGraphDataset($stats);
$datasetOverall = \App\Http\Controllers\GraphController::StatsToGraphDataset($statsOverallGraph);

@endphp


<script type="module">

    import {graphSet} from "../../../public/js/graph.js";
    
    let datasetWeekly = {!! json_encode($datasetWeekly) !!};
    graphSet("ChartWeekly",datasetWeekly)

    let datasetOverall = {!! json_encode($datasetOverall) !!};
    graphSet("ChartOverall",datasetOverall);

</script>


</body>
</html>

@if(!$render)
    @include("includes.footer")
@endif
