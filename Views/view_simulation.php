<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Formulaire de sélection de joueurs aléatoires -->
    <div class="selection">
        <h3>Sélectionner un Nombre de Joueurs</h3>
        <form action="?controller=partie&action=selectRandomJoueurs" method="POST" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
            <input type="number" id="nombre" name="nombre" min="1" max="100" required style="width: 50px;">
            <button type="submit" class="generate-button">Afficher les joueurs</button>
        </form>
    </div>
    
    <!-- Section de sélection des joueurs créés -->
    <div class="container">
        <h3>Liste des Joueurs Créés</h3>
        <form id="selection-form" action="?controller=partie&action=addSelectedJoueursCreer" method="POST">
            <div class="users-list">
                <h4>Joueurs</h4>
                <div class="header-row">
                    <input type="checkbox" id="select-all" onclick="selectAllCheckboxes(this)">
                    <label for="select-all">Sélectionner tous</label>
                </div>
                
                <!-- Liste des joueurs créés avec cases à cocher -->
                <?php if (!empty($joueurs_creer)): ?>
                    <?php foreach ($joueurs_creer as $joueur): ?>
                        <div class="data-row">
                            <input type="checkbox" name="selected_joueurs[]" value="<?= $joueur['id_joueur'] ?>" class="select-checkbox">
                            <span class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?> - <?= htmlspecialchars($joueur['ticket']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun joueur créé à afficher.</p>
                <?php endif; ?>
            </div>
            <!-- Bouton pour valider la sélection -->
            <button type="submit" class="generate-button">Ajouter les joueurs sélectionnés</button>
        </form>
    </div>

    <!-- Liste des joueurs en cours -->
    <div class="users-list">
        <h3>Joueurs en Cours</h3>
        <?php if (!empty($joueurs)): ?>
            <div class="data-rows" id="data-rows">
                <?php foreach ($joueurs as $joueur): ?>
                    <div class="data-row" data-id="<?= $joueur['id_joueur'] ?>">
                        <div class="user-item">Pseudo : <?= htmlspecialchars($joueur['pseudo']) ?></div>
                        <div class="ticket-item">Ticket : <?= htmlspecialchars($joueur['ticket']) ?></div>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <!-- Boutons Modifier et Supprimer -->
                            <button type="button" class="edit-button" onclick="showEditForm(<?= $joueur['id_joueur'] ?>, '<?= htmlspecialchars($joueur['pseudo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($joueur['ticket'], ENT_QUOTES) ?>')">🖊️ Modifier</button>
<!-- Formulaire de suppression -->
                            <form action="?controller=partie&action=deleteUser&id_joueur=<?= $joueur['id_joueur'] ?>" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce joueur ?');">
                            <button type="submit" class="delete-button">🗑️ Supprimer</button>
                        </form>                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun joueur à afficher.</p>
        <?php endif; ?>
    </div>

    <!-- Formulaire de modification caché au départ -->
    <div id="edit-form-container" class="form-container" style="display: none;">
        <h2>Modifier un Utilisateur</h2>
        <form action="?controller=partie&action=editUser" method="POST" onsubmit="return prepareTicket()">
            <input type="hidden" id="edit-id_joueur" name="id_joueur">
            <input type="hidden" id="type-joueur" name="type_joueur"> <!-- Ajout du champ caché -->
            <label for="edit-pseudo">Pseudo :</label>
            <input type="text" id="edit-pseudo" name="pseudo" required>
            <div id="error-message"></div>
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
</body>
</html>
