<?php require_once "view_begin.php"; ?>

<div class="container">
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

    .container {
        width: 100%;
        max-width: 800px;
        margin-top: 20px;
        background-color: #ffffff;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: center;
    }

    .page-title {
        text-align: center;
        font-size: 2em;
        color: #333;
    }

    /* Ticket gagnant */
    .winning-ticket {
        text-align: center;
        padding: 15px;
        background-color: #e8f5e9;
        border: 1px solid #c8e6c9;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        font-size: 1.2em;
        color: #388e3c;
        font-weight: bold;
        width: 100%;
    }

    /* Table des gagnants */
    .winner-list {
        text-align: center;
        width: 100%;
    }

    .winner-list h3 {
        color: #444;
        font-size: 1.5em;
        margin-bottom: 15px;
    }

    .
