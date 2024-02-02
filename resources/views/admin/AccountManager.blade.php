@include("includes.header")

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des dossiers</title>
    <link rel="stylesheet" href="{{asset("css/table.css")}}">
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Gestion des comptes utilisateurs</h1>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        @if($user->is_banned)
                            <form action="{{ route('user.unban', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Unban</button>
                            </form>
                        @else
                            <form action="{{ route('user.ban', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Ban</button>
                            </form>
                        @endif

                        <form action="{{ route('user.reset-password', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning">Reset Password</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
@include("includes.footer")
