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
    <div id="resultblock" class="flex flex-wrap justify-center"></div>
</div>

<script>
    document.getElementById('search').addEventListener('input', () => doSearch());

    function doSearch() {
        document.getElementById('resultblock').innerHTML = '';
        var query = document.getElementById('search').value;

        fetch('/do_search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                query: query,
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
