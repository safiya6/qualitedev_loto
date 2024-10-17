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
</div>

<style>
    /* Style général de la page */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fb;
        color: #444;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    h2 {
        text-align: center;
        color: #333;
        font-size: 2em;
        margin-bottom: 20px;
    }

    /* Ticket gagnant */
    .winning-ticket {
        text-align: center;
        padding: 15px;
        background-color: #e8f5e9;
        border: 1px solid #81c784;
        margin-bottom: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .winning-ticket h3 {
        margin-top: 0;
        color: #388e3c;
        font-size: 1.5em;
    }

    .winning-ticket p {
        font-size: 1.2em;
        font-weight: bold;
        color: #2e7d32;
    }

    /* Table des gagnants */
    .winner-list h3 {
        color: #444;
        font-size: 1.8em;
        text-align: center;
    }

    .winners-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .winners-table th,
    .winners-table td {
        padding: 12px 15px;
        text-align: center;
    }

    .winners-table th {
        background-color: #1976d2;
        color: #ffffff;
        font-weight: normal;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .winners-table tr {
        transition: background-color 0.3s ease;
    }

    .winners-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .winners-table tr:hover {
        background-color: #e3f2fd;
    }

    .winners-table td {
        color: #666;
    }
</style>
