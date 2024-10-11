<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélectionner des Utilisateurs Aléatoires</title>
</head>
<body>
    <h1>Sélection d'Utilisateurs Aléatoires</h1>

    <!-- Formulaire pour sélectionner le nombre d'utilisateurs -->
    <form action="?controller=joueurs&action=selectRandomUsers" method="POST">
        <label for="nombre">Nombre d'utilisateurs à sélectionner :</label>
        <input type="number" id="nombre" name="nombre" min="1" required>
        <button type="submit">Sélectionner</button>
    </form>

    <?php if (isset($users) && !empty($users)): ?>
        <h2>Utilisateurs sélectionnés</h2>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    ID: <?= htmlspecialchars($user['id_joueur']) ?>, 
                    Pseudo: <?= htmlspecialchars($user['pseudo']) ?>, 
                    Ticket: <?= htmlspecialchars($user['ticket']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($users) && empty($users)): ?>
        <p>Aucun utilisateur n'a été trouvé pour la sélection.</p>
    <?php endif; ?>

    <?php if (isset($message)): ?>
        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</body>
</html>
