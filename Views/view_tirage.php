<?php require_once "view_begin.php"; ?>

<div class="container">
    <!-- Titre de la page -->
    <h2>Tirage au Sort des Gagnants</h2>

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
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .winner-list {
        margin-top: 20px;
    }

    .winners-table {
        width: 100%;
        border-collapse: collapse;
    }

    .winners-table th, .winners-table td {
        border: 1px solid #dee2e6;
        padding: 10px;
        text-align: center;
    }

    .winners-table th {
        background-color: #343a40;
        color: #ffffff;
    }

    .winners-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #007bff;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background-color: #0056b3;
    }

    .rerun {
        text-align: center;
    }
</style>

<script>
    // Pour des évolutions futures, vous pouvez ajouter ici des scripts JavaScript si nécessaire.
</script>

<?php require_once "view_end.php"; ?>
