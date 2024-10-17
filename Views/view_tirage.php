<?php require_once "view_begin.php"; ?>

<div class="container-winning">
    <!-- Titre de la page -->
    <h2 class="page-title">Tirage au Sort des Gagnants</h2>

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
                        <th>Ecart</th>
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
                            <td><?= htmlspecialchars($winner['ecart']) ?></td>
                            <td><?= number_format($winner['gain'], 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a class="button-tirage" href="?controller=partie">Refaire une simulation</a>
        <?php else: ?>
            <p>Aucun gagnant à afficher.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Style général de la page */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fb;
        color: #444;
        display: flex;
        justify-content: center;
        padding: 20px;
        margin: 0;
    }

    
    .
