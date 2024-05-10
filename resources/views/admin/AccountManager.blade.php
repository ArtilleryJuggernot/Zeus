@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des dossiers</title>
    <link rel="stylesheet" href="{{ asset("css/table.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>

<body class="bg-gray-100">

@if (session("failure"))
    <h2>{{ session("failure") }}</h2>
@endif

@if (session("success"))
    <h2>{{ session("successi") }}</h2>
@endif

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-4 text-center">Gestion des comptes utilisateurs</h1>
    <table class="table-auto w-full">
        <thead>
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Nom</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->id }}</td>
                <td class="border px-4 py-2">{{ $user->name }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2">
                    @if ($user->is_ban)
                        <form action="{{ route("user.unban", $user->id) }}" method="POST" style="display: inline">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Unban
                            </button>
                        </form>
                    @else
                        <form action="{{ route("user.ban", $user->id) }}" method="POST" style="display: inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Ban
                            </button>
                        </form>
                    @endif

                    <form action="{{ route("user.reset-password", $user->id) }}" method="POST" style="display: inline">
                        @csrf
                        @method("PATCH")
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Reset Password
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>

@include("includes.footer")

</html>

