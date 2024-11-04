<div class="flex flex-col items-center space-y-4">
    <input type="text" wire:model.live="confirmName" placeholder="Entrez le nom de l’utilisateur pour confirmer"
           class="border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" />

    <button
        @if (!$isConfirmed) disabled @endif
    wire:click="deleteUser"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4 disabled:opacity-50">
        Supprimer le compte de l’utilisateur
    </button>

    @if (session()->has('message'))
        <div class="text-green-500 mt-3">{{ session('message') }}</div>
    @elseif (session()->has('error'))
        <div class="text-red-500 mt-3">{{ session('error') }}</div>
    @endif
</div>
