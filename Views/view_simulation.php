<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Affichage du message d'erreur -->
    <?php if (isset($message) && $message): ?>
        <div id="error-message" style="color: red; margin-bottom: 20px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Liste des utilisateurs -->
    <div class="users-list">
        <h3>Joueurs inscrits</h3>
        <div class="header-row">
            <div class="header-item">Pseudo</div>
            <div class="header-item">Ticket</div>
        </div>
        <div class="data-rows">
            <?php foreach ($joueurs as $joueur): ?>
                <div class="data-row" data-id="<?= $joueur['id_joueur'] ?>">
                    <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                    <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>

                    <!-- Delete Button -->
                    <form action="?controller=joueurs&action=deleteUser" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce joueur ?');">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <button type="submit" class="delete-button">
                            🗑️ Supprimer
                        </button>
                    </form>

                    <!-- Edit Button -->
                    <form action="?controller=joueurs&action=editUser" method="POST">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <button type="submit" class="edit-button">
                            🖊️ Modifier
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire d'ajout ou de modification d'utilisateur -->
    <div class="form-container">
        <h2>Ajouter ou Modifier un Utilisateur</h2>
        <form action="?controller=joueurs&action=addUser" method="POST">
            <input type="hidden" name="id_joueur" value="<?= $_POST['id_joueur'] ?? '' ?>">
            <input type="hidden" name="action_type" value="add">

            <div class="form-group">
                <label for="pseudo">Choisissez un pseudo :</label>
                <input type="text" name="pseudo" value="<?= $_POST['pseudo'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="numbers">Entrez vos numéros (séparés par des virgules) :</label>
                <input type="text" name="numbers" value="<?= $_POST['numbers'] ?? '' ?>" placeholder="ex : 1, 2, 3, 4, 5" required>
            </div>

            <div class="form-group">
                <label for="stars">Entrez vos étoiles (séparées par des virgules) :</label>
                <input type="text" name="stars" value="<?= $_POST['stars'] ?? '' ?>" placeholder="ex : 1, 2" required>
            </div>

            <button type="submit" class="generate-button">Valider</button>
        </form>
    </div>
</div>
</body>
</html>
