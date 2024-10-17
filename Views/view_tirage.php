<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Titre de la page -->
    <h2>Tirage au Sort des Gagnants</h2>

    <!-- Affichage du ticket gagnant -->
    <div class="winning-ticket">
        <h3>Ticket Gagnant</h3>
        <p><?= htmlspecialchars($_SESSION['winningTicket']) ?></p>
    </div>

    <!-- Section des gagnants du tirage -->
    <div class="winner-list">
        <h3>Liste des Gagnants</h3>
        <?php if (!empty($_SESSION['topWinners'])): ?>
            <table class="winners-table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Pseudo</th>
                        <th>Ticket</th>
                        <th>Numéros Égalité</th>
                        <th>Étoiles Égalité</th>
                        <th>Gain (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['topWinners'] as $index => $winner): ?>
                        <tr>
                            <td><?= htmlspecialchars($index + 1) ?></td>
                            <td><?= htmlspecialchars($winner['pseudo']) ?></td>
                            <td><?= htmlspecialchars($winner['ticket']) ?></td>
                            <td><?= htmlspecialchars($winner['numero_egalite']) ?></td>
                            <td><?= htmlspecialchars($winner['etoile_egalite']) ?></td>
                            <td><?= number_format($winner['gain'], 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun gagnant à afficher.</p>
        <?php endif; ?>
    </div>

    <!-- Bouton pour relancer un tirage -->
    <div class="rerun">
        <a href="?controller=gagnant&action=default" class="button">Relancer le Tirage</a>
    </div>
</div>

<style>
    /* Styles pour le ticket gagnant */
    .winning-ticket {
        text-align: center;
        padding: 10px;
        background-color: #f1f8e9;
        border: 1px solid #c5e1a5;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .winning-ticket h3 {
        margin-top: 0;
        color: #388e3c;
    }
    .winning-ticket p {
        font-size: 1.2em;
        font-weight: bold;
        color: #2e7d32;
    }
</style>
