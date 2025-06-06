<div class="note_instance bg-white rounded-lg shadow-md relative border-2 border-black p-4 m-2 flex flex-col justify-between">
    <!-- Nom et lien de la note -->
    <a class="note-link text-blue-500 font-bold hover:underline" href="{{ route('note_view', $note['id']) }}">
        <h3 class="flex items-center text-lg">
            📝 <span class="ml-2">{{ $note['name'] }}</span>
        </h3>
    </a>

    <!-- Boutons d'action -->
    <div class="action flex justify-between items-center mt-4 space-x-2">
        <button wire:click="startEditing" class="px-3 py-1 bg-green-600 text-white font-bold rounded hover:bg-green-700 border border-black">
            ✏️ Modifier
        </button>

        <form action="{{ route('delete_note') }}" method="post" class="inline">
            @csrf
            <input name="id" type="hidden" value="{{ $note['id'] }}" />
            <button title="Supprimer la note" class="px-3 py-1 bg-red-600 text-white font-bold rounded hover:bg-red-700 border border-black">
                ❌ Supprimer
            </button>
        </form>

        <form action="{{ route('downloadNote', ['id' => $note['id']]) }}" method="post" class="inline">
            @csrf
            <button type="submit" title="Télécharger la note" class="px-3 py-1 bg-blue-600 text-white font-bold rounded hover:bg-blue-800 border border-black">
                ⬇️ Télécharger
            </button>
        </form>
    </div>

    <!-- Catégories de la note -->
    <div class="list-cat flex flex-wrap gap-2 mt-4">
        @foreach ($note['categories'] as $category)
            @php
                $category = \App\Models\Categorie::find($category->categorie_id);
            @endphp
            <span class="category px-3 py-1 text-sm font-semibold text-white rounded" style="background-color: {{ $category->color }}">
                {{ $category->category_name }}
            </span>
        @endforeach
    </div>

    <!-- Mode édition si activé -->
    @if($isEditing)
        <div class="mt-4 p-4 border border-black bg-gray-100">
            <input type="text" wire:model="note.name" class="w-full border border-gray-300 rounded p-2">
            <div class="mt-2 flex space-x-2">
                <button wire:click="stopEditing" class="bg-blue-500 text-white font-bold px-3 py-1 rounded border border-black">Sauvegarder</button>
                <button wire:click="stopEditing" class="bg-gray-400 text-white font-bold px-3 py-1 rounded border border-black">Annuler</button>
            </div>
        </div>
    @endif
</div>
