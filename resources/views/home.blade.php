<div>
</div>

@include("includes.header")
<h1>Hello {{$user->name}}</h1>
<main>
    <section class="folders">
        <h2>Dossiers</h2>
        <!-- Affichage des dossiers -->
        <div class="folder-item">
            <h3>Nom du dossier 1</h3>
            <!-- Liste des note les plus récentes pour ce dossier -->
            <ul class="recent-notes">
                <li>Note 1</li>
                <li>Note 2</li>
                <li>Note 3</li>
                <!-- ... -->
            </ul>
            <!-- Liste des tâches les plus récentes pour ce dossier -->
            <ul class="recent-tasks">
                <li>Tâche 1</li>
                <li>Tâche 2</li>
                <li>Tâche 3</li>
                <!-- ... -->
            </ul>
        </div>
        <div class="folder-item">
            <!-- ... -->
        </div>
        <!-- ... -->
    </section>
</main>
</body>
</html>

