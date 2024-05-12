@include("includes.header")

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />

    @if ($parent_content["id"] == "Racine")
        <title>Racine des dossiers - Zeus</title>
    @else
        <title>Dossier {{ $folder->name }} - Zeus</title>
    @endif

    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/forms/formsFolder.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
</head>

<body class="background">
<div id="notification" class="notification">
    <div class="progress"></div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <h2 class="font-bold">Il y a eu des erreurs</h2>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="arborescence">
    <h2 class="mb-4 text-2xl font-bold text-center leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">Arborescence - {{$folder->name}}</h2>
    <br>
    @php
        $folder_tree = \App\Http\Controllers\FolderController::generateFolderTree($folder->id);
    @endphp
    @foreach ($folder_tree as $index => $folder_arbo)
        <a class="folder_arbo font-bold" href="{{ route("folder_view", $folder_arbo["id"]) }}">
            @if($index == 0) 🏠 @else 📁 @endif
            {{ $folder_arbo["name"] }}
        </a>
        @if($index  < count($folder_tree) - 1)
            <span class="folder_separator_arbo"> > </span>
        @endif
    @endforeach
</div>

<div class="add-section">
    <div class="add-btn" id="add-note-btn">
        <button class="add-btn-trigger">Ajouter une note 📝</button>
        <form class="add-form" action="{{ route("add_note") }}" method="post">
            <label for="add-note" class="font-bold">Entrez la note que vous souhaitez ajouter :</label>
            <input name="add-note" type="text" class="border border-gray-500 rounded-md p-2" />
            <input type="hidden" name="path_current" value="{{ $folder_path }}" />
            <input type="submit" value="Envoyer" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
            @csrf
        </form>
    </div>

    <div class="add-btn" id="add-folder-btn">
        <button class="add-btn-trigger">Ajouter un dossier 📁</button>
        <form class="add-form" action="{{ route("add_folder") }}" method="post">
            <label for="add-dossier" class="font-bold">Entrez le nom du dossier que vous souhaitez ajouter :</label>
            <input name="add-dossier" type="text" class="border border-gray-500 rounded-md p-2" />
            <input type="hidden" name="path_current" value="{{ $folder_path }}" />
            <input type="submit" value="Envoyer" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" />
            @csrf
        </form>
    </div>
</div>

<div class="folders">
    <!-- Boucle pour afficher les dossiers -->

    @if (empty($folderContents))
        <h4 class="font-bold"> > Le dossier est vide</h4>
    @endif

    @foreach ($folderContents as $item)
        <div class="folder-card  border-gray-500 rounded-md p-4 mb-4">
            @if ($item["type"] == "folder")
                <div class="folder_instance">
                    <a class="folder-link underline text-xl" href="{{ route("folder_view", $item["id"]) }}">
                        <h3>
                            📁 -
                            <span id="folder-name-{{ $item["id"] }}" class="font-bold">{{ $item["name"] }}</span>
                        </h3>
                    </a>

                    <div class="action flex mt-5">


                    <div class="edit-name">
                        <button class=" edit-label px-5 py-2 bg-green-600" data-id="{{ $item["id"] }}" data-type="{{ $item["type"] }}">✏️</button>
                    </div>
                    <div class="delete ">
                        <form action="{{ route("delete_folder") }}" method="post">
                            <input name="id" type="hidden" value="{{ $item["id"] }}" />
                            <button title="Supprimer le dossier" class="del px-5 py-2" type="submit">❌</button>
                            @csrf
                        </form>
                    </div>

                        <div class="download">
                            <form action="{{ route('downloadFolder', ['id' => $item["id"] ]) }}" method="post">
                              <button type="submit"  class=" bg-blue-600 hover:bg-blue-800 px-5 py-2" title="Télécharger le dossier">⬇️</button>
                                @csrf
                            </form>
                        </div>

                    </div>
                    <div class="list-cat">
                            @foreach ($item["categories"] as $category => $id)
                                @php
                                    $category = \App\Models\Categorie::find($category);
                                @endphp

                                <div class="category" style="background-color: {{ $category->color }}">{{ $category->category_name }}</div>
                            @endforeach
                        </div>

                </div>
            @else
                <div class="note_instance">
                    <a class="note-link underline text-xl" href="{{ route("note_view", $item["id"]) }}">
                        <h3>
                            📝 -
                            <span id="note-name-{{ $item["id"] }}" class="font-bold">{{ $item["name"] }}</span>
                        </h3>
                    </a>
                    <div class="action flex mt-5">
                    <div class="edit-name">
                    <button class="edit-label px-5 py-2 bg-green-600" data-id="{{ $item["id"] }}" data-type="{{ $item["type"] }}">✏️</button>
                    </div>

                    <div class="delete">
                        <form action="{{ route("delete_note") }}" method="post">
                            <input name="id" type="hidden" value="{{ $item["id"] }}" />
                            <button title="Supprimer la note" class="del px-5 py-2" type="submit">❌</button>
                            @csrf
                        </form>
                        <div class="list-cat">
                            @foreach ($item["categories"] as $category)
                                @php
                                    $category = \App\Models\Categorie::find($category->categorie_id);
                                @endphp

                                <div class="category" style="background-color: {{ $category->color }}">{{ $category->category_name }}</div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
<div class="cat_display">
    <h2 class="font-bold">Liste des catégories</h2>

    @foreach ($ressourceCategories as $category)
        @php
            $category = \App\Models\Categorie::find($category->categorie_id);
        @endphp

        <div class="category" style="background-color: {{ $category->color }}">{{ $category->category_name }}</div>
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
        <input name="ressourceId" value="{{ $folder->id }}" type="hidden" />
        <input name="ressourceType" value="folder" type="hidden" />
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




@if ($folder->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <button class="accordion">Section Partage utilisateur</button>
    <div class="panel">
        <h3 class="font-bold">Section partage utilisateur</h3>

        <p>
            Vous pouvez partagez ce dossier (et les notes et les dossiers qui sont à l'intérieur) à d'autre utilisateur
        </p>

        <div class="add-share">
            <form action="{{ route("add_folder_share") }}" method="post">
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
                <input type="hidden" name="folder_id" value="{{ $folder->id }}" />
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

        <script>
            function handleSaveButtonClick(button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Empêche le comportement par défaut du lien

                    const resourceId = this.getAttribute('data-id');
                    const resourceType = this.getAttribute('data-type');
                    const textareaElement = document.getElementById(
                        `edit-${resourceType}-name-${resourceId}`,
                    );
                    const newLabel = textareaElement.value.trim();
                    const user_id =
                        {{ \Illuminate\Support\Facades\Auth::user()->id }};

                    console.log(resourceId);
                    console.log(resourceType);
                    console.log(newLabel);
                    console.log(user_id);

                    // Faites une requête API pour mettre à jour le libellé
                    fetch('/api/update-label', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', // Si vous utilisez le jeton CSRF
                        },
                        body: JSON.stringify({
                            id: resourceId,
                            type: resourceType,
                            label: newLabel,
                            userId: {{ Auth::id() }}, // Utilisez l'ID de l'utilisateur authentifié
                        }),
                    })
                        .then((response) => {
                            console.log(response.json());

                            if (response.status === 200) {
                                // Mettez à jour le libellé dans l'interface utilisateur
                                const spanElement = document.getElementById(
                                    `${resourceType}-name-${resourceId}`,
                                );
                                spanElement.textContent = newLabel;

                                // Affichez à nouveau le bouton d'édition du libellé
                                const editButton = document.querySelector(
                                    `.edit-label[data-id="${resourceId}"][data-type="${resourceType}"]`,
                                );
                                editButton.style.display = 'inline';

                                // Restaure l'attribut href du lien <a>
                                this.parentElement.children[0].setAttribute(
                                    'href',
                                    editButton.dataset.hrefBackup,
                                );
                            }
                        })
                        .then((data) => {})
                        .catch((error) => {
                            console.error('Error updating label:', error);
                        });
                });
            }
        </script>

        <script>
            // Ajoutez un écouteur d'événements aux boutons d'édition de libellé
            document.querySelectorAll('.edit-label').forEach((button) => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Empêche le comportement par défaut du lien

                    const resourceId = this.getAttribute('data-id');
                    const resourceType = this.getAttribute('data-type');
                    const spanElement = document.getElementById(
                        `${resourceType}-name-${resourceId}`,
                    );
                    const currentLabel = spanElement.textContent.trim();

                    // Remplacez le contenu du span par un textarea
                    spanElement.innerHTML = `<textarea id="edit-${resourceType}-name-${resourceId}" rows="1">${currentLabel}</textarea>`;

                    // Ajoutez un bouton de validation
                    spanElement.innerHTML += `<button class="save-label" data-id="${resourceId}" data-type="${resourceType}">Save</button>`;

                    // Sauvegarde de l'attribut href du lien <a>

                    console.log(this);
                    let a_parent = this.parentElement.children[0];
                    console.log(a_parent);
                    this.dataset.hrefBackup = a_parent.getAttribute('href');
                    // Supprime l'attribut href du lien <a>
                    a_parent.removeAttribute('href');
                    // Cachez le bouton d'édition du libellé
                    this.style.display = 'none';
                    handleSaveButtonClick(
                        spanElement.querySelector('.save-label'),
                    );
                });
            });
        </script>

        <script src="{{ asset("js/accordeon.js") }}"></script>
    </body>

    <script src="{{ asset("js/notification.js") }}"></script>

    <script>
        @if(session("success"))
            showNotification("{{ session("success") }}", 'success');
            @elseif(session("failure"))
            showNotification("{{ session("success") }}", 'failure');
            @endif
    </script>
</html>

@include("includes.footer")
