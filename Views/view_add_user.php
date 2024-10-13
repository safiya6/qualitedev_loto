<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Liste des utilisateurs -->
    <div class="users-list">
        <h3>Joueurs inscrits</h3>
        <div class="header-row">
            <div class="header-item">Pseudo</div>
            <div class="header-item">Ticket</div>
        </div>
        <div class="data-rows">
            <?php foreach ($joueurs as $joueur): ?>
                <div class="data-row">
                    <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                    <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>
                    <!-- Formulaire pour le bouton de suppression -->
                    <form action="?controller=joueurs&action=deleteUser" method="POST" class="delete-form">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <button type="submit" class="delete-button">🗑️</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire d'ajout d'utilisateur -->
    <div class="form-container">
        <h2>Ajouter un Utilisateur</h2>
        <form action="?controller=joueurs&action=addUser" method="POST" onsubmit="return prepareTicket()">
            <div class="form-group">
                <label for="pseudo">Choisissez un pseudo :</label>
                <input type="text" id="pseudo" name="pseudo" required>
            </div>

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

            <button type="button" class="generate-button" onclick="generateRandomSelection()">
                <i>🎲</i> Générer aléatoirement
            </button>

            <!-- Champs masqués pour stocker les numéros et les étoiles -->
            <input type="hidden" id="numbers" name="numbers">
            <input type="hidden" id="stars" name="stars">

            <button type="submit" class="generate-button">Ajouter l'utilisateur</button>
        </form>
    </div>
</div>

<script src="Utils/fonction_add_user.js"></script> 
</body>
</html>
