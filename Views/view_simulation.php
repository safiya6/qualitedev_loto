<?php require_once "view_begin.php"; ?>

<div class="container">
    <h3>Sélectionner un Nombre de Joueurs à Afficher</h3>
    
    <!-- Formulaire pour sélectionner un nombre de joueurs -->
    <form action="?controller=partie&action=selectRandomJoueurs" method="POST">
        <label for="nombre">Nombre de joueurs (entre 1 et 100) :</label>
        <input type="number" id="nombre" name="nombre" min="1" max="100" required>
        <button type="submit">Afficher les joueurs</button>
    </form>
    
    <?php if (!empty($joueurs)): ?>
        <h3>Joueurs Sélectionnés</h3>
        <div class="data-rows">
            <?php foreach ($joueurs as $joueur): ?>
                <div class="data-row">
                    <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                    <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun joueur à afficher.</p>
    <?php endif; ?>
</div>

    </body>
    </html>
