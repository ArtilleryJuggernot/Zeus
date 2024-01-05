@include("includes.header")


@if(session("success"))
    <h3>{{session("success")}}</h3>
@endif

<form action="{{ route('store_task') }}" method="POST">
    @csrf <!-- Ajout du jeton CSRF pour la sécurité -->
    <label for="tache_name">Nom de la tâche:</label>

    <br>

    <input type="text" id="tache_name" name="tache_name" required>

    <br>

    <label for="">La tache à t'elle une fin limite ?</label>
    <br>
    <input id="is_due" type="checkbox" name="is_due">
    <input required disabled id="dt_input" type="date" name="dt_input">

    <input type="submit" value="Créer la tâche">
</form>

<script>
    function enableDate(){
        let radio_is_due = document.getElementById("is_due").checked
        let dt_input = document.getElementById("dt_input").disabled = !radio_is_due;
    }

    document.getElementById("is_due").addEventListener("click" ,() => enableDate());

</script>
