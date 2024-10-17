<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Formulaire de sélection de joueurs aléatoires -->
    <div class="selection">
        <h3>Sélectionner un Nombre de Joueurs</h3>
        <form action="?controller=partie&action=selectRandomJoueurs" method="POST" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
            <input type="number" name="nombre" min="1" max="100" required style="width: 50px;">
            <button type="submit" class="generate-button">Afficher les joueurs</button>
        </form>
    </div>    

    <!-- Liste des joueurs en cours -->
    <div class="users-list">
        <h3>Joueurs en Cours</h3>
        <?php if (!empty($joueurs)): ?>
            <div class="data-rows">
                <?php foreach ($joueurs as $joueur): ?>
                    <?php $type_joueur = isset($joueur['id_joueur_creer']) && $joueur['id_joueur_creer'] !== null ? 'creer' : 'pred'; ?>
                    <div class="data-row" data-id="<?= $joueur['id_joueur'] ?>">
                        <div class="user-item">Pseudo : <?= htmlspecialchars($joueur['pseudo']) ?></div>
                        <div class="ticket-item">Ticket : <?= htmlspecialchars($joueur['ticket']) ?></div>
                        <!-- Bouton Modifier 
                        <a href="?controller=partie&action=editUserForm&id_joueur=<?= $joueur['id_joueur'] ?>&type_joueur=<?= $type_joueur ?>" class="edit-button">🖊️ Modifier</a>-->
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

    <!-- Formulaire de modification utilisateur -->
    <?php if (isset($joueur_edit) && !empty($joueur_edit)): ?>
    <div class="form-container">
        <h2>Modifier un Utilisateur</h2>
        <form action="?controller=partie&action=editUser" method="POST">
            <input type="hidden" name="id_joueur" value="<?= $joueur_edit['id_joueur'] ?>">
            <input type="hidden" name="type_joueur" value="<?= $type_joueur ?>">
            
            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" value="<?= htmlspecialchars($joueur_edit['pseudo']) ?>" required>
            
            <label>Numéros :</label>
            <input type="text" name="numbers" placeholder="Ex: 1,2,3,4,5">
            
            <label>Étoiles :</label>
            <input type="text" name="stars" placeholder="Ex: 1,2">
            
            <button type="submit" class="generate-button">Modifier l'utilisateur</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Liste des joueurs créés -->
    <div class="selection-container">
        <h3>Liste des Joueurs Créés</h3>
        <form action="?controller=partie&action=addSelectedJoueursCreer" method="POST">
            <div class="users-list">
                <div class="header-row">
                    <input type="checkbox" id="select-all">
                    <label for="select-all">Sélectionner tous</label>
                </div>
                <?php if (!empty($joueurs_creer)): ?>
                    <?php foreach ($joueurs_creer as $joueur): ?>
                        <div class="data-row">
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
</body>
</html>
