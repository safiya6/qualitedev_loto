<?php require_once "view_begin.php"; ?>

<div class="container">
    <h3>Sélectionner un Nombre de Joueurs</h3>

    <!-- Formulaire pour sélectionner un nombre de joueurs -->
   
    <!-- Liste des joueurs en cours -->
    <div class="users-list">
        <form action="?controller=partie&action=selectRandomJoueurs" method="POST">
            <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
            <input type="number" id="nombre" name="nombre" min="1" max="100" required>
            <button type="submit" class="generate-button">Afficher les joueurs</button>
        </form>

        <h3>Joueurs en Cours</h3>

        <div class="header-row">
            <div class="header-item">Pseudo</div>
            <div class="header-item">Ticket</div>
        </div>

        <div class="data-rows" id="data-rows">
            <?php if (!empty($joueurs)): ?>
                <?php foreach ($joueurs as $joueur): ?>
                    <div class="data-row" data-id="<?= $joueur['id_joueur_pred'] ?>">
                        <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                        <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>
                        <!-- Boutons de modification et suppression -->
                        <button type="button" class="edit-button" onclick="showEditForm(<?= $joueur['id_joueur_pred'] ?>, '<?= htmlspecialchars($joueur['pseudo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($joueur['ticket'], ENT_QUOTES) ?>')">🖊️ Modifier</button>

                        <button type="button" class="delete-button" onclick="deleteUser(<?= $joueur['id_joueur_pred'] ?>)">🗑️ Supprimer</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun joueur à afficher.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulaire de modification caché au départ -->
    <div id="edit-form-container" class="form-container" style="display: none;">
        <h2>Modifier un Utilisateur</h2>
        <form action="?controller=partie&action=editUser" method="POST" onsubmit="return prepareTicket()">
            <input type="hidden" id="edit-id_joueur" name="id_joueur">
            <div class="form-group">
                <label for="edit-pseudo">Pseudo :</label>
                <input type="text" id="edit-pseudo" name="pseudo" required>
            </div>
            <div id="error-message" style="display: none; color: red; margin-bottom: 20px;"></div>

            <label>Choisissez vos numéros :</label>
            <div class="number-grid">
                <?php for ($i = 1; $i <= 49; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'number')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <label>Choisissez vos étoiles :</label>
            <div class="star-grid">
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'star')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <button type="button" class="generate-button" onclick="generateRandomSelection()">🎲 Générer aléatoirement</button>
            <input type="hidden" id="numbers" name="numbers">
            <input type="hidden" id="stars" name="stars">
            <button type="submit" class="generate-button">Modifier l'utilisateur</button>
        </form>
    </div>
</div>

<script src="Utils/fonction_add_user.js"></script>
<script>
function deleteUser(id_joueur) {
    if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return;
    fetch(`?controller=partie&action=deleteUser&id_joueur=${id_joueur}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`.data-row[data-id='${id_joueur}']`);
            if (row) row.remove();
        } else {
            alert("Erreur lors de la suppression du joueur.");
        }
    })
    .catch(error => console.error("Erreur AJAX:", error));
}
</script>

</body>
</html>