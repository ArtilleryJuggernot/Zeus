@include("includes.header")

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>🏆 Éditeur d'Habitude</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" />
    <link rel="stylesheet" href="{{ asset('css/notification/notification.css') }}" />
    <style>
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: pop 0.5s cubic-bezier(.4,0,.2,1) both; }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-pink-50 to-yellow-50 min-h-screen font-sans text-gray-900">

<!-- Notification animée -->
<div id="notification" class="fixed top-24 right-6 bg-green-500 text-white font-bold py-2 px-4 rounded shadow-lg transition-opacity duration-300 opacity-0 z-50 flex items-center space-x-2">
    <span id="notif-emoji">✅</span>
    <span id="notif-text"></span>
</div>

<div class="mx-auto max-w-3xl p-4 animate-pop">
    <!-- Titre principal -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-center mb-2 bg-gradient-to-r from-blue-600 via-pink-500 to-yellow-400 text-transparent bg-clip-text drop-shadow-lg flex items-center justify-center gap-2">🏆 Éditeur d'Habitude</h1>
    <p class="text-lg text-center text-gray-500 mb-8">Modifiez votre routine, ajustez les jours et horaires selon vos besoins !</p>

    <!-- Formulaire d'édition d'habitude -->
    <div class="w-full max-w-2xl mx-auto bg-white/90 rounded-2xl shadow-xl p-8 border-2 border-blue-200 mb-10 animate-pop">
        <h2 class="font-bold text-2xl mb-4 text-blue-700 flex items-center gap-2">✏️ Modifier l'habitude : <span class="text-pink-600">{{ $habitude->name }}</span></h2>
        <form id="form-hab" action="{{ route('update_habitude') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}"/>
            <input type="hidden" name="habit_id" value="{{$habitude->id}}"/>
            <div class="flex flex-wrap gap-4 justify-center">
                @php $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche']; @endphp
                @foreach($jours as $i => $jour)
                <div class="flex flex-col items-center bg-gradient-to-br from-blue-100 via-pink-50 to-yellow-50 border-2 border-blue-200 rounded-2xl shadow p-4 min-w-[160px] animate-pop">
                    <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2">{{ $jour }}</button>
                    <input type="hidden" name="day_{{ $i }}" id="day_{{ $i }}" value="{{array_key_exists($i,$habits_possede) ? 1 : 0}}" />
                    <div class="time-container {{ array_key_exists($i,$habits_possede) ? 'block' : 'hidden' }} w-full">
                        <label class="block mb-1 text-xs font-medium text-gray-900">Heure de début :</label>
                        <input name="{{ strtolower($jour) }}-start" type="time" class="rounded-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 p-2.5" min="00:00" max="23:59" value="{{isset($habits_possede[$i]["start"]) ? $habits_possede[$i]["start"] : '08:00'}}" required>
                        <label class="block mt-2 mb-1 text-xs font-medium text-gray-900">Heure de fin :</label>
                        <input name="{{ strtolower($jour) }}-stop" type="time" class="rounded-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 p-2.5" min="00:00" max="23:59" value="{{isset($habits_possede[$i]["stop"]) ? $habits_possede[$i]["stop"] : '12:00'}}" required>
                    </div>
                </div>
                @endforeach
            </div>
            <button id="submit-form-hab" type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                <span class="text-xl">💾</span> Enregistrer les modifications
            </button>
        </form>
    </div>
</div>

<script src="{{ asset('js/notification.js') }}"></script>
<script>
    // Notification animée
    function showNotification(message, type = 'success') {
        const notif = document.getElementById('notification');
        const notifText = document.getElementById('notif-text');
        const notifEmoji = document.getElementById('notif-emoji');
        notifText.textContent = message;
        notifEmoji.textContent = type === 'success' ? '✅' : '❌';
        notif.classList.remove('opacity-0');
        notif.classList.add('opacity-100');
        setTimeout(() => {
            notif.classList.remove('opacity-100');
            notif.classList.add('opacity-0');
        }, 3000);
    }
    @if (session('success'))
    showNotification("{{ session('success') }}", 'success');
    @elseif (session('failure'))
    showNotification("{{ session('failure') }}", 'failure');
    @endif
</script>
<script>
    // Gestion dynamique des jours/horaires (boutons)
    let btn_hab = document.getElementsByClassName("btn-hab");
    Array.prototype.forEach.call(btn_hab, (btn, idx) => {
        let div_parent = btn.parentElement;
        let child = div_parent.querySelector('input[type="hidden"]');
        let timeContainer = div_parent.querySelector('.time-container');
        btn.addEventListener('click', () => {
            if(child.value === '1'){
                child.value = 0;
                btn.classList.replace("from-blue-500","from-red-500")
                btn.classList.replace("via-blue-600","via-red-600")
                btn.classList.replace("to-blue-700","to-red-700")
                timeContainer.classList.add("hidden");
                timeContainer.classList.remove("block");
            } else {
                child.value = 1;
                btn.classList.replace("from-red-500","from-blue-500")
                btn.classList.replace("via-red-600","via-blue-600")
                btn.classList.replace("to-red-700","to-blue-700")
                timeContainer.classList.remove("hidden");
                timeContainer.classList.add("block");
            }
        });
    });
    // Validation avant soumission
    document.getElementById("submit-form-hab").addEventListener("click", (e) => {
        let days = Array.from({length: 7}, (_, i) => document.getElementById("day_"+i));
        let atLeastOne = days.some(d => d.value === "1");
        if(!atLeastOne){
            e.preventDefault();
            alert("Au moins un jour doit être sélectionné");
            return;
        }
        // Vérification des horaires
        let jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
        for(let i=0;i<7;i++){
            if(days[i].value === "1"){
                let start = document.querySelector(`input[name='${jours[i].toLowerCase()}-start']`).value;
                let stop = document.querySelector(`input[name='${jours[i].toLowerCase()}-stop']`).value;
                if(start >= stop){
                    e.preventDefault();
                    alert(`Pour le jour ${jours[i]}, l'heure de début doit être avant l'heure de fin et elles doivent être différentes.`);
                    return;
                }
            }
        }
    });
</script>

@include("includes.footer")
</body>
</html>
