@include("includes.header")
<form action="{{ route('store_folder') }}" method="POST">
    @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
    <label for="folder_name">Nom du dossier :</label>
    <input type="text" id="folder_name" name="folder_name" required>

    <p>Emplacement :</p>
    <input type="radio" id="root" name="location" value="root" required>
    <label for="root">À la racine</label><br>

    <input @if($folders->count() == 0) disabled @endif type="radio" id="sub_folder" name="location" value="sub_folder" required>
    <label for="sub_folder">Dans un autre dossier :</label>
    <select @if($folders->count() == 0) disabled @endif name="parent_folder_id" id="parent_folder_id">
        @foreach($folders as $folder)
            @php
                $folder_name = explode("/",$folder->path);
            @endphp
            <option value="{{ $folder->folder_id }}">{{ end($folder_name) }} </option>
        @endforeach
    </select>
    <br>



    <input type="submit" value="Créer le dossier">
</form>

<!-- ... Autres éléments de la page ... -->
