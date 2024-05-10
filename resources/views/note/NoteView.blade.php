@include("includes.header")
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Editeur de Note</title>
        <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
        <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
        <link rel="stylesheet" href="{{ asset("css/note/editor.css") }}" />
        <link
            rel="stylesheet"
            href="{{ asset("css/notification/notification.css") }}"
        />
        @vite('resources/css/app.css')
        <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
        <script
            type="module"
            src="{{ asset("js/stack_edit/stack_edit_note.js") }}"
        ></script>

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

        <div id="notification" class="notification">
            <div class="progress"></div>
        </div>

        <script>
            var content = {!! json_encode($content) !!};
                @if(\Illuminate\Support\Facades\Auth::user()->id == $note->owner_id)
                    const perm = "F"; // L'utilisateur propriétaire à tout les droits
                @else
                    const perm = "{{ $perm_user->perm }}";
                @endif

                const csrf = '{{ csrf_token() }}';
                const note_id =  '{{ $note->id }}';
                const user_id = '{{ \Illuminate\Support\Facades\Auth::user()->id }}';
        </script>

        <h1 class=" pt-5 mb-4 text-2xl font-bold text-center leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">Editeur de Note - {{ $note->name }}</h1>

        @if ($note->owner_id != \Illuminate\Support\Facades\Auth::user()->id)
            <h3 class="it">
                Vous êtes sur la note de
                {{ \App\Models\User::find($note->owner_id)->name }}
            </h3>
            <h3 class="it">
                Vous avez des droits de :
                @if ($perm_user->perm == "RO")
                    Lecture Seule
                @elseif ($perm_user->perm == "RW")
                    Lecture et Ecriture
                @elseif ($perm_user->perm == "F")
                    Total
            sur cette note
                @endif
            </h3>
        @endif

        <div id="editor_md"></div>

        <div class="flex justify-center">
            <button class="space_btn bg-green-600 px-2 py-2 text-white font-bold mr-2" onclick="saveNote()">
                Sauvegarder la note
            </button>

            <button class="space_btn bg-green-600 px-2 py-2 text-white font-bold ml-2" onclick="downloadPDF()">
                Télécharger le PDF
            </button>
        </div>

        <div class="cat_display">
            <h2>Liste des catégories</h2>

            @foreach ($ressourceCategories as $category)
                @php
                    $category = \App\Models\Categorie::find($category->categorie_id);
                @endphp

                <div
                    class="category"
                    style="background-color: {{ $category->color }}"
                >
                    {{ $category->category_name }}
                </div>
            @endforeach
        </div>

        <button class="accordion">Gestion des categories</button>
        <div class="panel">
            <h2 class="font-bold">Gestion des categories</h2>
            <form method="post" action="{{ route("addCategory") }}">
                @csrf
                <label for="category" class="font-bold">Ajouter une catégorie :</label>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="category" id="category">
                    @foreach ($notOwnedCategories as $categoryId => $categoryName)
                        <option value="{{ $categoryId }}">{{ $categoryName }}</option>
                    @endforeach
                </select>
                <input name="ressourceId" value="{{ $note->id }}" type="hidden" />
                <input name="ressourceType" value="note" type="hidden" />
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</button>
            </form>

            <form method="post" action="{{ route("removeCategory") }}">
                @csrf
                <label for="removeCategory" class="font-bold">Supprimer une catégorie :</label>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="removeCategory" id="removeCategory">
                    @foreach ($ressourceCategories as $categoryId => $category)
                        <option value="{{ $category->id }}">{{ \App\Models\Categorie::find($category->categorie_id)->category_name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
            </form>
        </div>




        @if ($note->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
            <button class="accordion">Section Partage utilisateur</button>
            <div class="panel">
                <h3 class="font-bold">Section partage utilisateur</h3>

                <p>
                    Vous pouvez partagez ce dossier (et les notes et les dossiers qui sont à l'intérieur) à d'autre utilisateur
                </p>

                <div class="add-share">
                    <form action="{{ route("add_note_share") }}" method="post">
                        <label for="id_share" class="font-bold">Entrez l'identifiant de la personne à qui vous souhaitez partagez la note :</label>
                        <input name="id_share" type="number" min="0" class="border border-gray-500 rounded-md p-2" />

                        <br />
                        <br />
                        <label for="right" class="font-bold">Selectionnez le droit que l'utilisateur aura sur la note</label>
                        <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="right" class="border border-gray-500 rounded-md p-2">
                            <option value="RO">Lecture Seul (Read Only)</option>
                            <option value="RW">Lecture et Ecriture</option>
                            <option value="F">Tout (Lecture , Ecriture, Suppression, Renommer)</option>
                        </select>
                        <input type="hidden" name="note_id" value="{{ $note->id }}" />
                        <input type="submit" value="Envoyer" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
                        @csrf
                    </form>
                </div>
            </div>
            <button class="accordion">Listes des autorisations utilisateurs</button>
            <div class="panel">
                <h3 class="font-bold">Liste des autorisations utilisateurs</h3>

                <table class="border border-black">
                    <thead>
                    <tr>
                        <th class="border border-black">Nom d'utilisateur</th>
                        <th class="border border-black">ID de l'utilisateur</th>
                        <th class="border border-black">Droit</th>
                        <th class="border border-black">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($usersPermissionList as $perm)
                        <tr>
                            <td class="border border-black">{{ \App\Models\User::find($perm->dest_id)->name }}</td>
                            <!-- Remplacez 'name' par le champ correspondant dans le modèle User -->
                            <td class="border border-black">{{ $perm->dest_id }}</td>
                            <td class="border border-black">{{ $perm->perm }}</td>
                            <td class="border border-black">
                                <form action="{{ route("delete_perm", ["id" => $perm->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </body>

    <script src="{{ asset("js/accordeon.js") }}"></script>

    <script src="{{ asset("js/notification.js") }}"></script>

    <script>
        @if(session("success"))
        showNotification("{{ session("success") }}", 'success');
        @elseif(session("failure"))
        showNotification("{{ session("success") }}", 'failure');
        @endif
    </script>

@include("includes.footer")
