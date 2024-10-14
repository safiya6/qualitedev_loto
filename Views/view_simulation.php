<?php require_once "view_begin.php"; ?>

<div class="container">
    <h3>Sélectionner un Nombre de Joueurs</h3>
    
    <!-- Formulaire pour sélectionner un nombre de joueurs -->
    <form id="player-selection-form" action="?controller=partie&action=selectRandomJoueurs" method="POST">
        <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
        <input type="number" id="nombre" name="nombre" min="1" max="100" required>
        <button type="button" class="generate-button" onclick="loadPlayers()">Afficher les joueurs</button>
    </form>

    <!-- Liste des joueurs en cours -->
    <div class="users-list">
        <h3>Joueurs en Cours</h3>

        <div class="header-row">
            <div class="header-item">Pseudo</div>
            <div class="header-item">Ticket</div>
        </div>

        <div class="data-rows" id="player-list">
            <!-- La liste des joueurs sera chargée dynamiquement ici -->
        </div>
    </div>

    <!-- Formulaire de modification caché au départ -->
    <div id="edit-form-container" class="form-container" style="display: none;">
        <h2>Modifier un Utilisateur</h2>
        <form onsubmit="event.preventDefault(); submitEditForm();">
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
        <div id="success-message" style="display: none; color: green; margin-top: 20px;">Modification réussie!</div>
    </div>
</div>

<script src="Utils/fonction_add_user.js"></script>
</body>
</html>
