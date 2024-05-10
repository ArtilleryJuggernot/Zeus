@include('includes.header')

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editeur de Tâche - {{$task->task_name}} - Zeus</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <link rel="stylesheet" href="{{asset("css/category.css")}}">
    <link rel="stylesheet" href="{{asset("css/tableau.css")}}">
    <link rel="stylesheet" href="{{asset("css/note/editor.css")}}">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="{{asset("css/notification/notification.css")}}">
    <script type="module" src="{{asset("js/stack_edit/stack_edit_task.js")}}"></script>

</head>
<body>


<script>
    var content = {!! json_encode($task->description) !!};
    @if(\Illuminate\Support\Facades\Auth::user()->id == $task->owner_id)
        const perm = "F"; // L'utilisateur propriétaire à tout les droits
    @else
        const perm = "{{$perm_user->perm }}";
    @endif

    const csrf = '{{csrf_token()}}';
    const task_id =  '{{ $task->id}}';
    const user_id = '{{\Illuminate\Support\Facades\Auth::user()->id}}';
</script>

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


<h1 class="pt-5 mb-4 text-2xl font-bold text-center leading-none tracking-tight text-gray-900 md:text-5xl lg:text-3xl dark:text-white">Editeur de Tâche - {{$task->task_name}}</h1>

@if($task->owner_id != \Illuminate\Support\Facades\Auth::user()->id)

    <h3 class="it">Vous êtes sur la tâche de {{\App\Models\User::find($task->owner_id)->name}}</h3>
    <h3 class="it">Vous avez des droits de :
    @if($perm_user == "F")
        Total
        @else
            @if($perm_user->perm == "RO") Lecture Seule
            @elseif($perm_user->perm == "RW") Lecture et Ecriture
            @elseif($perm_user->perm == "F" ) Total
            sur cette tâche
            @endif
    @endif
    </h3>


@endif

<div class="center">
    <label class="text-xl">La tâche est t'elle fini ?</label>
    <input id="is_finish" type="checkbox" @if($task->is_finish) checked @endif name="is_finish">
</div>

<div id="editor_md">

</div>





<div class="flex justify-center ">


    <button class="space_btn bg-green-600 px-2 py-2 text-white font-bold mr-2" onclick="saveTask()">
        <span class="emoji">💾 </span>
        Sauvegarder la tache
    </button>

<div class="delete">
    <form action="{{route("delete_task")}}" method="post">
        <input name="id" type="hidden" value="{{$task->id}}"/>
        <button class="space_btn bg-red-600 px-2 py-2 text-white font-bold ml-2" type="submit"><span class="emoji">🗑</span>️ Supprimer la tâche</button>
        @csrf
    </form>
</div>


</div>
<span class="center text-xl">Attention ! Supprimer une tâche implique qu'elle sera supprimé dans tout les projets</span>



<div class="cat_display">

    <h2>Liste des catégories</h2>

    @foreach($ressourceCategories as $category)
        @php
            $category = \App\Models\Categorie::find($category->categorie_id);
        @endphp
        <div class="category" style="background-color: {{ $category->color }};">
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
        <input name="ressourceId" value="{{ $task->id }}" type="hidden" />
        <input name="ressourceType" value="task" type="hidden" />
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




@if ($task->owner_id == \Illuminate\Support\Facades\Auth::user()->id)
    <button class="accordion">Section Partage utilisateur</button>
    <div class="panel">
        <h3 class="font-bold">Section partage utilisateur</h3>

        <p>
            Vous pouvez partagez cette tâche à d'autre utilisateur
        </p>

        <div class="add-share">
            <form action="{{ route("add_task_share") }}" method="post">
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
                <input type="hidden" name="task_id" value="{{ $task->id }}" />
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

<!-- Ajoutez ce code dans votre vue HTML -->

<script src="{{asset("js/notification.js")}}"></script>


<script>
    @if(session("success"))
    showNotification("{{session("success")}}", 'success');
    @elseif(session("failure"))
    showNotification("{{session("success")}}", 'failure');
    @endif
</script>

<script src="{{asset("js/shortcut_editor.js")}}"></script>





</body>
</html>


@include("includes.footer")
