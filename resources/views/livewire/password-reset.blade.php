<div>
    <button wire:click="resetPassword" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Réinitialiser le mot de passe de l'utilisateur
    </button>

    @if (session()->has('message'))
        <div class="text-green-500 mt-3">
            {{ session('message') }}
        </div>
    @endif
</div>
