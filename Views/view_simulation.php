<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Formulaire pour sélectionner un nombre de joueurs -->
    <div class="form-container">
        <h2>Sélectionner un Nombre de Joueurs</h2>
        <form action="?controller=partie&action=selectRandomJoueurs" method="POST">
            <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
            <input type="number" id="nombre" name="nombre" min="1" max="100" required>
            <button type="submit" class="generate-button">Afficher les joueurs</button>
        </form>
    </div>

    <!-- Liste des joueurs sélectionnés -->
    <div class="users-list">
        <h3>Joueurs Sélectionnés</h3>
        
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
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun joueur à afficher.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
