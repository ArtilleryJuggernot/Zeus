<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("css/search/searchbar.css") }}">
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>
<body class="bg-gray-100">
<div class="overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-70 z-50 hidden" id="container">
    <h2 id="searchtitle" class="text-white text-center text-3xl font-bold mt-16">Que voulez-vous chercher ?</h2>
    <textarea id="search" class="w-full h-80vh mx-auto block border border-gray-500 rounded-md p-2 mt-10"
              placeholder="Recherche..."></textarea>
    <div class="flex flex-wrap justify-center gap-4 mt-4">
        <label class="flex items-center space-x-2 bg-white rounded shadow px-3 py-2 hover:bg-blue-50 transition cursor-pointer">
            <input type="checkbox" id="filter_notes" checked class="accent-blue-600 w-5 h-5">
            <span class="font-semibold text-blue-700">Notes</span>
        </label>
        <label class="flex items-center space-x-2 bg-white rounded shadow px-3 py-2 hover:bg-yellow-50 transition cursor-pointer">
            <input type="checkbox" id="filter_folders" checked class="accent-yellow-500 w-5 h-5">
            <span class="font-semibold text-yellow-700">Dossiers</span>
        </label>
        <label class="flex items-center space-x-2 bg-white rounded shadow px-3 py-2 hover:bg-green-50 transition cursor-pointer">
            <input type="checkbox" id="filter_tasks" checked class="accent-green-600 w-5 h-5">
            <span class="font-semibold text-green-700">Tâches</span>
        </label>
        <label class="flex items-center space-x-2 bg-white rounded shadow px-3 py-2 hover:bg-pink-50 transition cursor-pointer">
            <input type="checkbox" id="filter_projects" checked class="accent-pink-600 w-5 h-5">
            <span class="font-semibold text-pink-700">Projets</span>
        </label>
        <label id="task_status_label" style="display:inline;" class="flex items-center space-x-2 bg-white rounded shadow px-3 py-2 ml-2">
            <span class="font-semibold text-gray-700">Statut tâche :</span>
            <select id="filter_task_status" class="ml-2 border border-gray-300 rounded p-1 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition">
                <option value="all" selected>Toutes les tâches</option>
                <option value="done">Tâches terminées</option>
                <option value="not_done">Tâches non terminées</option>
            </select>
        </label>
    </div>
    <div id="resultblock" class="flex flex-wrap justify-center"></div>
</div>

@php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
@endphp



@if($agent->isMobile() ||$agent->isTablet() )
    <button class="btn-searchbar  fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-gray-300 text-gray-800 py-3 px-6 rounded-lg shadow-md hover:bg-gray-400 hover:shadow-lg transition duration-300">
        Rechercher une ressource 🔎
    </button>
@endif


<script>
    document.getElementById('search').addEventListener('input', () => doSearch());
    document.getElementById('filter_notes').addEventListener('change', () => doSearch());
    document.getElementById('filter_folders').addEventListener('change', () => doSearch());
    document.getElementById('filter_tasks').addEventListener('change', () => doSearch());
    document.getElementById('filter_projects').addEventListener('change', () => doSearch());
    document.getElementById('filter_task_status').addEventListener('change', () => doSearch());

    document.getElementById('filter_tasks').addEventListener('change', function() {
        document.getElementById('task_status_label').style.display = this.checked ? 'inline' : 'none';
    });

    function doSearch() {
        document.getElementById('resultblock').innerHTML = '';
        var query = document.getElementById('search').value;
        if (query.length < 3) {
            return;
        }
        var filter_notes = document.getElementById('filter_notes').checked;
        var filter_folders = document.getElementById('filter_folders').checked;
        var filter_tasks = document.getElementById('filter_tasks').checked;
        var filter_projects = document.getElementById('filter_projects').checked;
        var filter_task_status = document.getElementById('filter_task_status').value;
        fetch('/do_search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                query: query,
                filter_notes: filter_notes,
                filter_folders: filter_folders,
                filter_tasks: filter_tasks,
                filter_projects: filter_projects,
                filter_task_status: filter_task_status
            }),
        }).then(async (response) => {
            const jsonString = await response.json();
            var data = JSON.parse(jsonString);
            let div = document.getElementById('resultblock');
            document.getElementById('resultblock').innerHTML = '';
            data.forEach((elem) => {
                elem = JSON.parse(elem);
                var child = document.createElement('a');
                child.classList.add('search');
                child.classList.add("font-bold");
                if (elem['type'] == 'folder') {
                    child.href = '/view_folder/' + elem['id'];
                    child.innerHTML = '<h3>' + '📁 ' + elem['name'] + '</h3>';
                    div.appendChild(child);
                }
                if (elem['type'] == 'note') {
                    child.href = '/note_view/' + elem['id'];
                    child.innerHTML = '<h3>' + '📝 ' + elem['name'] + '</h3>';
                }
                if (elem['type'] == 'task') {
                    child.href = '/view_task/' + elem['id'];
                    child.innerHTML = '<h3>' + '📚 ' + elem['name'] + '</h3>';
                }
                if (elem['type'] == 'project') {
                    child.href = '/projet_view/' + elem['id'];
                    child.innerHTML = '<h3>' + '🚧 ' + elem['name'] + '</h3>';
                }
                child.classList.add(elem['type']);
                div.appendChild(child);
                div.innerHTML += '<br>';
            });
        });
    }

    let status = 0;
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            if (status) hideOverlay();
            else showOverlay();
        }
    });

    document.getElementsByClassName("btn-searchbar")[0].addEventListener("click", () => {
        if(status) hideOverlay();
        else showOverlay();
        }

    )

    function showOverlay() {
        document.getElementById('container').style.display = 'block';
        status = 1;
        document.getElementById('search').focus();
        document.getElementById('search').click();
    }

    function hideOverlay() {
        document.getElementById('container').style.display = 'none';
        status = 0;
    }
</script>
</body>
</html>
