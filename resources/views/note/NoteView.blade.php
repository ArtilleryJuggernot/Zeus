@include('includes.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editeur de Note</title>
    <style>
        /* Ajoutez vos styles CSS pour l'éditeur de note ici */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: auto;
            overflow-y: auto;
        }

        .note-editor {
            width: 100%;
            display: flex;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .it{
            font-style: italic;
        }

        textarea {
            height: 700px;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #preview {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #fff;
            min-height: 400px;
            overflow-y: auto;
        }

        /* Style pour le bouton */
        button {
            margin-bottom: 20px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        td {
            border: 1px solid black
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body>

@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Il y a eu des erreurs</h2>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session("success"))
    <h3>{{session("success")}}</h3>
@endif




<h1>Editeur de Note - {{$note->name}}</h1>


@if($note->owner_id != \Illuminate\Support\Facades\Auth::user()->id)

    <h3 class="it">Vous êtes sur la note de {{\App\Models\User::find($note->owner_id)->name}}</h3>
    <h3 class="it">Vous avez des droits de :
        @if($perm_user->perm == "RO") Lecture Seule
        @elseif($perm_user->perm == "RW") Lecture et Ecriture
        @elseif($perm_user->perm == "F" ) Total
        sur cette note
        @endif</h3>
@endif

<div class="note-editor">

    <textarea id="note-content" rows="10" cols="50">{{$content}}</textarea>


    <div id="preview"></div>
</div>

<button onclick="saveNote()">Sauvegarder la note</button>



@if($note->owner_id == \Illuminate\Support\Facades\Auth::user()->id)

<h1>Section partage utilisateur</h1>

<p>Vous pouvez partagez cette note à d'autre utilisateur</p>

<div class="add-share">
    <form action="{{route("add_note_share")}}" method="post">
        <label for="id_share">Entrez l'identifiant de la personne à qui vous souhaitez partagez la note :</label>
        <input name="id_share" type="number" min="0"/>

        <br>
        <br>
        <label for="right">Selectionnez le droit que l'utilisateur aura sur la note</label>
        <select name="right">
            <option value="RO">Lecture Seul (Read Only)</option>
            <option value="RW">Lecture et Ecriture</option>
            <option value="F">Tout (Lecture , Ecriture, Suppression, Renommer)</option>
        </select>
        <input type="hidden" name="note_id" value="{{$note->note_id}}">
        <input type="submit" value="Envoyer" />

        @csrf
    </form>
</div>

<h1>Liste des autorisations utilisateurs</h1>

<table>
    <thead>
    <tr>
        <th>Nom d'utilisateur</th>
        <th>ID de l'utilisateur</th>
        <th>Droit</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($usersPermissionList as $perm)
        <tr>
            <td>{{ \App\Models\User::find($perm->dest_id)->name }}</td> <!-- Remplacez 'name' par le champ correspondant dans le modèle User -->
            <td>{{ $perm->dest_id }}</td>
            <td>{{ $perm->perm }}</td>
            <td>
                <form action="{{ route('delete_perm', ['id' => $perm->id]) }}" method="POST">
                    @csrf
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>




@endif



<script>
    function saveNote() {

        console.log("Dans save note");

        let content = document.getElementById('note-content').value;
        let perm = "";
        // Autorisation
        @if(\Illuminate\Support\Facades\Auth::user()->id == $note->owner_id)
            perm = "F"; // L'utilisateur propriétaire à tout les droits
        @else
            perm = "{{$perm_user->perm }}";
        @endif


        console.log(perm);

        fetch('/save-note', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Si vous utilisez le jeton CSRF
            },
            body: JSON.stringify({ content: content,
                note_id: {{ $note->note_id}},
                user_id: {{\Illuminate\Support\Facades\Auth::user()->id}},
                perm : perm
            })

        })
            .then(response => {
                if (response.ok) {
                    // Afficher un message de succès ou exécuter d'autres actions si nécessaire
                    console.log('Contenu sauvegardé avec succès!');
                } else {
                    console.error('Erreur lors de la sauvegarde du contenu.');
                }
            })
            .catch(error => {
                console.error('Erreur de connexion:', error);
            });
    }

    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 's') {
            // Prevent the Save dialog to open
            e.preventDefault();
            // Place your code here
            saveNote();
        }
    });

</script>

<script>
    function previewMarkdown() {
        console.log("sinj");
        // Fonction JavaScript pour prévisualiser le contenu Markdown
        let noteContent = document.getElementById('note-content').value;

        // Utilisation de la bibliothèque Marked.js pour convertir le Markdown en HTML
        let html = marked.marked(noteContent);
        document.getElementById('preview').innerHTML = html;
        saveNote()
    }

    // Appliquer le Markdown automatiquement lors de la saisie dans le textarea
    document.getElementById('note-content').addEventListener('input', () => previewMarkdown() );
    previewMarkdown();
</script>

<!-- Ajoutez ce code dans votre vue HTML -->



</body>
</html>
