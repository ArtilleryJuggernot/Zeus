@include("includes.header")

    <!DOCTYPE html>
<html>

<head>
    <title>Logs</title>
</head>

<body class="bg-gray-100 p-8">
<h1 class="text-3xl font-bold mb-4">Logs</h1>

<form action="{{ route("filter_logs") }}" method="GET" class="mb-8">
    <div class="flex mb-4">
        <label for="date_filter" class="mr-2">Date récente :</label>
        <select name="date_filter" id="date_filter" class="border rounded-md px-2 py-1">
            <option value="1">Oui</option>
            <option value="0">Non</option>
        </select>
    </div>
    <div class="flex mb-4">
        <label for="user_id" class="mr-2">ID de l'utilisateur :</label>
        <input type="text" name="user_id" id="user_id" class="border rounded-md px-2 py-1">
    </div>
    <div class="flex mb-4">
        <label for="action_filter" class="mr-2">Action :</label>
        <select name="action_filter" id="action_filter" class="border rounded-md px-2 py-1">
            <!-- Ajoutez ici les options dynamiquement depuis le contrôleur -->
            <option value="none">Pas de filtre de recherche</option>
            @foreach ($actions as $action)
                <option value="{{ $action->ACTION }}">
                    {{ $action->ACTION }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Rechercher</button>
</form>

<!-- Affichage des logs filtrés -->
@if ($filteredLogs->isNotEmpty())
    <h2 class="text-xl font-bold mb-4">Résultats de la recherche :</h2>
    <table class="table-auto w-full">
        <thead>
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Utilisateur ID</th>
            <th class="px-4 py-2">Action</th>
            <th class="px-4 py-2">Contenu</th>
            <th class="px-4 py-2">Ressource ID</th>
            <th class="px-4 py-2">Type de ressource</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($filteredLogs as $log)
            <tr>
                <td class="border px-4 py-2">{{ $log->id }}</td>
                <td class="border px-4 py-2">{{ $log->created_at }}</td>
                <td class="border px-4 py-2">{{ $log->user_id }}</td>
                <td class="border px-4 py-2">{{ $log->action }}</td>
                <td class="border px-4 py-2">{{ $log->content }}</td>
                <td class="border px-4 py-2">{{ $log->ressource_id }}</td>
                <td class="border px-4 py-2">{{ $log->ressource_type }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p class="text-lg">Aucun résultat trouvé pour les filtres spécifiés.</p>
@endif
</body>

@include("includes.footer")

</html>
