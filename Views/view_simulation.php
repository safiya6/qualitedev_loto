<?php require_once "view_begin.php"; ?>
<a class="button-tirage" href="?controller=gagnant" >
    Lancer le Tirage
</a>
<div class="container">
    <!-- Formulaire de sélection de joueurs aléatoires -->
    <div class="selection">
        <h3>Sélectionner un Nombre de Joueurs</h3>
        <form action="?controller=partie&action=selectRandomJoueurs" method="POST" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <label for="nombre">Nombre de joueurs (entre 1 et <?= $maxSelectable ?>) :</label>
            <input type="number" name="nombre" min="1" max="<?= $maxSelectable ?>" required style="width: 50px;">
            <button type="submit" class="generate-button">Afficher les joueurs</button>
        </form>
    </div>

    <!-- Liste des joueurs en cours -->
    <div class="users-list">
        <h3>Joueurs en Cours (<?= $nbJoueursEnCours ?>)</h3>
        <?php if (!empty($joueurs)): ?>
            <div class="data-rows">
                <?php foreach ($joueurs as $joueur): ?>
                    <div class="data-row" data-id="<?= $joueur['id_joueur'] ?>">
                        <div class="user-item">Pseudo : <?= htmlspecialchars($joueur['pseudo']) ?></div>
                        <div class="ticket-item">Ticket : <?= htmlspecialchars($joueur['ticket']) ?></div>
                        <form action="?controller=partie&action=deleteUser&id_joueur=<?= $joueur['id_joueur'] ?>" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce joueur ?');">
                            <button type="submit" class="delete-button">🗑️ Supprimer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun joueur à afficher.</p>
        <?php endif; ?>
    </div>

    <!-- Liste des joueurs créés -->
    <div class="selection-container">
        <h3>Liste des Joueurs Créés</h3>
        <form id="create-players-form" action="?controller=partie&action=addSelectedJoueursCreer" method="POST">
            <div class="users-list">
                <?php if (!empty($joueurs_creer)): ?>
                    <?php foreach ($joueurs_creer as $joueur): ?>
                        <div class="data-row" data-id="<?= $joueur['id_joueur'] ?>">
                            <input type="checkbox" name="selected_joueurs[]" value="<?= $joueur['id_joueur'] ?>">
                            <div class="user-item">Pseudo : <?= htmlspecialchars($joueur['pseudo']) ?></div>
                            <div class="ticket-item">Ticket : <?= htmlspecialchars($joueur['ticket']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun joueur créé à afficher.</p>
                <?php endif; ?>
            </div>
            <button type="submit" class="generate-button">Ajouter les joueurs sélectionnés</button>
        </form>
    </div>
</div>

<!-- Message d'erreur -->
<?php if (!empty($_SESSION['message_err'])): ?>
    <div class="error-message">
        <?= htmlspecialchars($_SESSION['message_err']) ?>
    </div>
    <?php unset($_SESSION['message_err']); ?>
<?php endif; ?>





</body>
</html>
