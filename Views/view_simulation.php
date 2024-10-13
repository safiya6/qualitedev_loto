<?php require_once "view_begin.php"; ?>

<div class="container">
    <h3>Sélectionner un Nombre de Joueurs</h3>

    <!-- Formulaire pour sélectionner un nombre de joueurs -->
    <form action="?controller=partie&action=selectRandomJoueurs" method="POST">
        <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
        <input type="number" id="nombre" name="nombre" min="1" max="100" required>
        <button type="submit" class="generate-button">Afficher les joueurs</button>
    </form>

    <!-- Liste des joueurs en cours -->
    <div class="users-list">
        <h3>Joueurs en Cours</h3>

        <div class="header-row">
            <div class="header-item">Pseudo</div>
            <div class="header-item">Ticket</div>
        </div>

        <div class="data-rows">
            <?php if (!empty($joueurs)): ?>
                <?php foreach ($joueurs as $joueur): ?>
                    <div class="data-row">
                        <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                        <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>

                        <!-- Boutons de modification et suppression -->
                        <!--<button type="button" class="edit-button" 
                            onclick="populateForm(')">
                            🖊️ Modifier
                        </button>
                        
                        <form action="?controller=partie&action=deleteUser" method="POST" class="delete-form">
                            <input type="hidden" name="id_joueur" value="<>">
                            <button type="submit" class="delete-button">🗑️ Supprimer</button>
                        </form>-->
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun joueur à afficher.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="Utils/fonction_add_user.js"></script> 
</body>
</html>
