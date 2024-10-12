<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <style>
        /* Styles pour un design minimaliste */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 800px;
        }

        .users-list, .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .users-list {
            width: 35%;
            text-align: left;
        }

        .users-list h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .header-row, .data-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-weight: bold;
        }

        .header-item, .user-item, .ticket-item {
            width: 50%;
        }

        .user-item, .ticket-item {
            color: #555;
        }

        .form-container {
            width: 65%;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 5px;
        }

        .number-grid, .star-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 15px;
            justify-content: center;
        }

        .number-grid button, .star-grid button {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            background-color: #eee;
            border: 1px solid #ddd;
            cursor: pointer;
            font-weight: bold;
        }

        .generate-button {
            margin-top: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .selected {
            background-color: #4CAF50;
            color: #fff;
        }

    </style>
</head>
<body>

<div class="container">
    <!-- Liste des utilisateurs -->
    <div class="users-list">
        <h3>Utilisateurs inscrits</h3>
            <div class="header-row">
                <div class="header-item">Pseudo</div>
                <div class="header-item">Ticket</div>
            </div>
        <div class="data-rows">
            <?php foreach ($joueurs as $joueur): ?>
                <div class="data-row">
                    <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                    <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire d'ajout d'utilisateur -->
    <div class="form-container">
        <h2>Ajouter un Utilisateur</h2>

        <form action="?controller=joueurs&action=addUser" method="POST" onsubmit="return prepareTicket()">
            <div class="form-group">
                <label for="pseudo">Choisissez un pseudo :</label>
                <input type="text" id="pseudo" name="pseudo" required>
            </div>

            <label>Choisissez vos numéros :</label>
            <div class="number-grid">
                <?php for ($i = 1; $i <= 49; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'number')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <label>Choisissez vos étoiles :</label>
            <div class="star-grid">
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'star')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <button type="button" class="generate-button" onclick="generateRandomSelection()">
                <i>🎲</i> Générer aléatoirement
            </button>

            <!-- Champs masqués pour stocker les numéros et les étoiles -->
            <input type="hidden" id="numbers" name="numbers">
            <input type="hidden" id="stars" name="stars">

            <button type="submit" class="generate-button">Ajouter l'utilisateur</button>
        </form>
    </div>
</div>

<script>
    let selectedNumbers = new Set();
    let selectedStars = new Set();

    function toggleSelection(button, type) {
        const value = parseInt(button.getAttribute("data-value"));
        if (type === 'number') {
            if (selectedNumbers.has(value)) {
                selectedNumbers.delete(value);
                button.classList.remove("selected");
            } else if (selectedNumbers.size < 5) {
                selectedNumbers.add(value);
                button.classList.add("selected");
            }
        } else if (type === 'star') {
            if (selectedStars.has(value)) {
                selectedStars.delete(value);
                button.classList.remove("selected");
            } else if (selectedStars.size < 2) {
                selectedStars.add(value);
                button.classList.add("selected");
            }
        }
    }

    function generateRandomSelection() {
        document.querySelectorAll('.number-grid button, .star-grid button').forEach(btn => btn.classList.remove("selected"));
        selectedNumbers.clear();
        selectedStars.clear();

        while (selectedNumbers.size < 5) selectedNumbers.add(Math.floor(Math.random() * 49) + 1);
        while (selectedStars.size < 2) selectedStars.add(Math.floor(Math.random() * 9) + 1);

        document.querySelectorAll('.number-grid button').forEach(btn => {
            if (selectedNumbers.has(parseInt(btn.getAttribute("data-value")))) btn.classList.add("selected");
        });
        document.querySelectorAll('.star-grid button').forEach(btn => {
            if (selectedStars.has(parseInt(btn.getAttribute("data-value")))) btn.classList.add("selected");
        });
    }

    function prepareTicket() {
        if (selectedNumbers.size !== 5 || selectedStars.size !== 2) {
            alert("Veuillez sélectionner 5 numéros et 2 étoiles.");
            return false;
        }
        document.getElementById("numbers").value = [...selectedNumbers].join(",");
        document.getElementById("stars").value = [...selectedStars].join(",");
        return true;
    }
</script>

</body>
</html>
