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
        }

        .note-editor {
            width: 100%;
            display: flex;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        textarea {
            height: 700px;
            width: 700px;
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body>

@php
$Notename = explode("/",$note->path);
@endphp

<h1>Editeur de Note - {{end($Notename)}}</h1>
<div class="note-editor">

    <textarea id="note-content" rows="10" cols="50">{{$content}}</textarea>


    <div id="preview"></div>
</div>

<button onclick="saveNote()">Sauvegarder la note</button>


<script>
    function previewMarkdown() {
        console.log("sinj");
        // Fonction JavaScript pour prévisualiser le contenu Markdown
        let noteContent = document.getElementById('note-content').value;

        // Utilisation de la bibliothèque Marked.js pour convertir le Markdown en HTML
        let html = marked.marked(noteContent);
        document.getElementById('preview').innerHTML = html;
    }

    // Appliquer le Markdown automatiquement lors de la saisie dans le textarea
    document.getElementById('note-content').addEventListener('input', () => previewMarkdown() );
    previewMarkdown();
</script>

<!-- Ajoutez ce code dans votre vue HTML -->
<script>
    function saveNote() {
        let content = document.getElementById('note-content').value;

        fetch('/save-note', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Si vous utilisez le jeton CSRF
            },
            body: JSON.stringify({ content: content,
                                    note_id: {{ $note->note_id}},
                                    user_id: {{\Illuminate\Support\Facades\Auth::user()->id}}
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


</body>
</html>
