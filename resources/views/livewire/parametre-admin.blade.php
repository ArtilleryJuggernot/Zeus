<div class="max-w-xl mx-auto mt-10 p-8 rounded-3xl shadow-2xl bg-white/80 backdrop-blur-lg border border-blue-400/30">
    <h1 class="text-2xl font-extrabold mb-6 text-center text-transparent bg-gradient-to-r from-blue-500 via-pink-400 to-yellow-400 bg-clip-text animate-gradient-move">Paramètres administrateur</h1>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <span class="font-semibold text-lg">Autoriser les inscriptions</span>
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" wire:model.live="allow_new_users" class="sr-only peer">
            <div class="w-14 h-7 bg-gray-200 rounded-full peer peer-checked:bg-blue-500 transition-all duration-300 relative">
                <div class="absolute left-1 top-1 bg-white w-5 h-5 rounded-full shadow transition-all duration-300 peer-checked:translate-x-7"></div>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-700">{{ $allow_new_users ? 'OUI' : 'NON' }}</span>
        </label>
    </div>

</div>