<?php require_once "view_begin.php"; ?>

<div class="container" style="display: flex; flex-direction: column; align-items: center; gap: 20px;">
    <h3>Sélectionner un Nombre de Joueurs</h3>

    <form action="?controller=partie&action=selectRandomJoueurs" method="POST" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
        <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
        <input type="number" id="nombre" name="nombre" min="1" max="100" required style="width: 50px;">
        <button type="submit" class="generate-button">Afficher les joueurs</button>
    </form>

    <!-- Liste des joueurs en cours -->
    <div class="users-list" style="display: flex; flex-direction: column; align-items: center; gap: 20px; width: 100%;">
        <h3>Joueurs en Cours</h3>

        <?php if (!empty($joueurs)): ?>
            <div class="data-rows" id="data-rows" style="display: flex; flex-direction: column; align-items: flex-start; gap: 15px;">
                <?php foreach ($joueurs as $joueur): ?>
                    <div class="data-row" data-id="<?= $joueur['id_joueur_pred'] ?>" style="display: flex; flex-direction: column; gap: 5px; padding: 10px; border-bottom: 1px solid #ddd; width: 100%;">
                        <div class="user-item">Pseudo : <?= htmlspecialchars($joueur['pseudo']) ?></div>
                        <div class="ticket-item">Ticket : <?= htmlspecialchars($joueur['ticket']) ?></div>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <button type="button" class="edit-button" onclick="showEditForm(<?= $joueur['id_joueur_pred'] ?>, '<?= htmlspecialchars($joueur['pseudo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($joueur['ticket'], ENT_QUOTES) ?>')">🖊️ Modifier</button>
                            <button type="button" class="delete-button" onclick="deleteUser(<?= $joueur['id_joueur_pred'] ?>)">🗑️ Supprimer</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun joueur à afficher.</p>
        <?php endif; ?>
    </div>

    <!-- Formulaire de modification caché au départ -->
    <div id="edit-form-container" class="form-container" style="display: none; flex-direction: column; align-items: center; gap: 15px;">
        <h2>Modifier un Utilisateur</h2>
        <form action="?controller=partie&action=editUser" method="POST" onsubmit="return prepareTicket()" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <input type="hidden" id="edit-id_joueur" name="id_joueur">
            <label for="edit-pseudo">Pseudo :</label>
            <input type="text" id="edit-pseudo" name="pseudo" required style="width: 150px;">
            <div id="error-message" style="display: none; color: red; margin-bottom: 20px;"></div>

            <label>Choisissez vos numéros :</label>
            <div class="number-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px;">
                <?php for ($i = 1; $i <= 49; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'number')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <label>Choisissez vos étoiles :</label>
            <div class="star-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px;">
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
</body>
</html>
