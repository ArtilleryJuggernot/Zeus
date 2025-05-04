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
    <h1 class="text-3xl font-extrabold mb-8 text-center text-transparent bg-gradient-to-r from-blue-500 via-pink-400 to-yellow-400 bg-clip-text animate-gradient-move">Gestion des comptes utilisateurs</h1>
    <form method="GET" class="flex flex-wrap justify-center gap-4 mb-6">
        <label class="font-semibold text-gray-700">Trier par :</label>
        <select name="sort" onchange="this.form.submit()" class="rounded-lg border px-4 py-2 focus:ring-2 focus:ring-blue-400">
            <option value="id_desc" @if($sort=='id_desc') selected @endif>Identifiant (décroissant)</option>
            <option value="id_asc" @if($sort=='id_asc') selected @endif>Identifiant (croissant)</option>
            <option value="last_login" @if($sort=='last_login') selected @endif>Dernière connexion</option>
            <option value="most_resources" @if($sort=='most_resources') selected @endif>Plus de ressources</option>
        </select>
    </form>
    <div class="overflow-x-auto rounded-2xl shadow-lg bg-white/80 backdrop-blur-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-blue-500 via-pink-400 to-yellow-400 text-white">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nom</th>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Ressources</th>
            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($users as $user)
            <tr class="hover:bg-blue-50 transition">
                <td class="px-6 py-4 font-bold text-blue-700">{{ $user->id }}</td>
                <td class="px-6 py-4">{{ $user->name }}</td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                <td class="px-6 py-4 text-center font-semibold">
                    {{ $user->notes()->count() + $user->folders()->count() + $user->tasks()->count() + $user->projets()->count() }}
                </td>
                <td class="px-6 py-4 flex flex-wrap gap-2 items-center">
                    @if ($user->is_ban)
                        <form action="{{ route('user.unban', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded transition">Unban</button>
                        </form>
                    @else
                        @if( \Illuminate\Support\Facades\Auth::user()->id != $user->id )
                        <form action="{{ route('user.ban', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded transition">Ban</button>
                        </form>
                        @endif
                    @endif
                    <form action="{{ route('admin.impersonate', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-3 rounded transition">Se connecter</button>
                    </form>
                    @if( \Illuminate\Support\Facades\Auth::user()->id != $user->id )
                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce compte utilisateur ? Cette action est irréversible.');">
                            @csrf
                            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-1 px-3 rounded transition">Supprimer</button>
                        </form>
                    @endif
                    <livewire:password-reset :userId="$user->id" />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

</body>

@include("includes.footer")

</html>

