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
    <<!-- Liste des joueurs en cours -->
<div class="users-list">
    <h3>Joueurs en Cours</h3>
    <?php if (!empty($joueurs)): ?>
         <!-- Bouton pour supprimer tous les joueurs -->
         <form action="?controller=partie&action=deleteAllUsers" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer tous les joueurs ?');">
            <button type="submit" class="delete-all-button">🗑️ Supprimer tous les joueurs</button>
        </form>
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
                <div class="header-row">
                    <input type="checkbox" id="select-all" onclick="toggleAllCheckboxes(this)">
                    <label for="select-all">Sélectionner tous</label>
                </div>
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
<a href="?controller=gagnant" style="
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s;
">
    Lancer le Tirage
</a>

<script>
    // Fonction pour masquer les joueurs sélectionnés lors de la soumission
    document.getElementById("create-players-form").addEventListener("submit", function() {
        // Récupère tous les checkboxes sélectionnés
        const selectedCheckboxes = document.querySelectorAll('#create-players-form input[name="selected_joueurs[]"]:checked');
        
        // Masque chaque joueur correspondant dans la liste des joueurs créés
        selectedCheckboxes.forEach(checkbox => {
            const playerRow = document.querySelector(`.data-row[data-id="${checkbox.value}"]`);
            if (playerRow) {
                playerRow.style.display = 'none';
            }
        });
    });

    // Fonction pour sélectionner ou désélectionner tous les joueurs
    function toggleAllCheckboxes(selectAllCheckbox) {
        const checkboxes = document.querySelectorAll('#create-players-form input[name="selected_joueurs[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }
</script>

</body>
</html>
