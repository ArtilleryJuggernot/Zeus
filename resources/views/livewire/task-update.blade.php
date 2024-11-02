<div class="task-card flex-grow margin-right bg-white rounded-lg shadow-md relative border-2 border-black p-4 pr-6 m-2" x-data="{ isEditing: false }">
    <a href="{{ route('view_task', $taskId) }}" class="text-blue-500 font-bold hover:underline">
        <span class="whitespace-nowrap">{{ $taskName }}</span>
    </a>
    @php
    // Si et seulement si il n'y a pas de priorité en paramètre du composant
    // On verifie manuellement
    if(!isset($priority)){
        $exist = \App\Models\task_priorities::where([
                    "user_id" => \Illuminate\Support\Facades\Auth::user()->id,
                    "task_id" =>$taskId,
                    ])->first();
        if ($exist)
            $priority = $exist;
    }
    @endphp


    @if(isset($priority) && $priority != "None") <p class="text-red-500">⚠️ {{ $priority }}</p> @endif
    @if ($dueDate)
        <div class="task-due-date">
            <p class="font-bold">🕐 <span>{{ \Carbon\Carbon::parse($dueDate)->format('Y-m-d') }}</span></p>
        </div>
        @if(isset($task->pos))
        <div class="task-pos-projet">
            <p class="font-bold text-gray-700">Position : {{ $taskT->pos }}</p>
        </div>
        @endif
    @endif
    <div class="task-is-finish">
        <p class="font-bold">
            {{ $is_finish ? '✅' : '⏸️' }} {{ $is_finish ? 'Finis' : 'En cours' }}
        </p>
    </div>
    <div class="flex items-center mt-2">
        <button wire:click="updateTaskStatus({{ $taskId }}, {{ $is_finish ? '0' : '1' }})" class="text-gray-500 hover:bg-green-500 p-2">
            <span>  {{$is_finish ? "⏸"  : "✅"}} </span>
        </button>


        <button @click="isEditing = true" class="text-gray-500 hover:text-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </button>
        <button type="button" wire:click="deleteTask({{ $taskId }})" class="del p-2 text-red-500 hover:bg-black">❌</button>
    </div>

    @php
    $categories = \App\Models\possede_categorie::where([
        "ressource_id" => $taskId,
        "type_ressource" => "task",
        "owner_id" => \Illuminate\Support\Facades\Auth::user()->id,
])->get();
    @endphp
    @if(!empty($categories))

    <div class="list-cat flex flex-wrap gap-2 mt-4">
        @foreach ($categories as $category)
            @php
                $category = \App\Models\Categorie::find($category->categorie_id);
            @endphp
            <span class="category px-3 py-1 text-sm font-semibold text-white rounded" style="background-color: {{ $category->color }}">
                {{ $category->category_name }}
            </span>
        @endforeach
    </div>
    @endif


    <!-- Modal pour modifier la tâche -->
    <div class="fixed inset-0 flex items-center justify-center z-50" style="display: none;" x-show="isEditing" @click.away="isEditing = false">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/2 h-1/2"> <!-- Taille de la modale augmentée -->
            <h2 class="text-lg font-bold">Modifier la Tâche</h2>
            <form wire:submit.prevent="updateTask">
                <div>
                    <label for="task_name" class="block">Nom de la Tâche:</label>
                    <input type="text" wire:model="taskName" class="border border-gray-300 p-2 rounded w-full" required>
                    @error('taskName') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="due_date" class="block">Date Limite:</label>
                    <input type="date" wire:model="dueDate" class="border border-gray-300 p-2 rounded w-full">
                </div>
                <div>
                    <label for="priority" class="block">Priorité:</label>
                    <select wire:model="priority" class="border border-gray-300 p-2 rounded w-full" required>
                        <option value="Urgence">Urgence</option>
                        <option value="Grande priorité">Grande priorité</option>
                        <option value="Prioritaire">Prioritaire</option>
                        <option value="None">Aucune</option>

                    </select>
                    @error('priority') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded">Enregistrer</button>
                <button type="button" @click="isEditing = false" class="mt-4 bg-red-500 text-white py-2 px-4 rounded">Annuler</button>
            </form>
        </div>
    </div>
</div>
