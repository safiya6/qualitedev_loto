<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Number Selector -->
    <div class="selection-panel">
        <h2>Sélectionner un Nombre de Joueurs</h2>
        <form action="?controller=partie&action=selectRandomJoueurs" method="POST">
            <label for="nombre">Nombre de joueurs (entre 1 et 100):</label>
            <input type="number" name="nombre" min="1" max="100" required>
            <button type="submit">Afficher les joueurs</button>
        </form>
    </div>

    <!-- Current Players Section -->
    <div class="current-players">
        <h2>Joueurs en Cours</h2>
        <?php foreach ($joueurs as $joueur): ?>
            <div class="player-entry">
                <p>Pseudo: <?= htmlspecialchars($joueur['pseudo']) ?> | Ticket: <?= htmlspecialchars($joueur['ticket']) ?></p>
                
                <!-- Edit Button -->
                <button onclick="populateForm(<?= $joueur['id_joueur'] ?>, '<?= htmlspecialchars($joueur['pseudo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($joueur['ticket'], ENT_QUOTES) ?>')">Modifier</button>
                
                <!-- Delete Button -->
                <form action="?controller=partie&action=deleteUser&id_joueur=<?= $joueur['id_joueur'] ?>" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce joueur ?');">
                    <button type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Created Players List -->
    <div class="created-players">
        <h2>Liste des Joueurs Créés</h2>
        <form action="?controller=partie&action=addSelectedJoueursCreer" method="POST">
            <?php foreach ($joueurs_creer as $joueur): ?>
                <div class="player-entry">
                    <input type="checkbox" name="selected_joueurs[]" value="<?= $joueur['id_joueur'] ?>">
                    <label><?= htmlspecialchars($joueur['pseudo']) ?> | Ticket: <?= htmlspecialchars($joueur['ticket']) ?></label>
                </div>
            <?php endforeach; ?>
            <button type="submit">Ajouter les joueurs sélectionnés</button>
        </form>
    </div>

    <!-- Edit/Add Form (Hidden by Default, Shows on Edit) -->
    <div id="user-form-container" style="display: none;">
        <h2>Modifier un Joueur</h2>
        <form id="user-form" action="?controller=partie&action=editUser" method="POST" onsubmit="return prepareTicket()">
            <input type="hidden" id="id_joueur" name="id_joueur">
            <input type="hidden" id="type_joueur" name="type_joueur" value="pred">

            <label for="pseudo">Pseudo:</label>
            <input type="text" id="pseudo" name="pseudo" required>

            <!-- Number Selection Grid -->
            <label>Numéros:</label>
            <div class="number-grid">
                <?php for ($i = 1; $i <= 49; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'number')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <!-- Star Selection Grid -->
            <label>Étoiles:</label>
            <div class="star-grid">
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'star')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <input type="hidden" id="numbers" name="numbers">
            <input type="hidden" id="stars" name="stars">
            <button type="submit">Valider</button>
        </form>
    </div>
</div>

<script src="Utils/fonction_add_user.js"></script>
<script>
// JavaScript functions for toggling selections, generating random pseudo, populating the form, etc.
// Insert JavaScript functions like toggleSelection, populateForm, and prepareTicket here.
</script>

</body>
</html>
