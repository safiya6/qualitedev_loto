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
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
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

        .generate-button i {
            margin-right: 5px;
        }

        .selected {
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ajouter un Utilisateur</h2>

    <form action="?controller=joueurs&action=addUser" method="POST" onsubmit="prepareTicket()">
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

        <!-- Champ caché pour stocker le ticket -->
        <input type="hidden" id="ticket" name="ticket">

        <button type="submit" class="generate-button">Ajouter l'utilisateur</button>
    </form>
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
        document.querySelectorAll('.number-grid button, .star-grid button').forEach(btn => {
            btn.classList.remove("selected");
        });
        selectedNumbers.clear();
        selectedStars.clear();

        while (selectedNumbers.size < 5) {
            let randNum = Math.floor(Math.random() * 49) + 1;
            selectedNumbers.add(randNum);
        }

        while (selectedStars.size < 2) {
            let randStar = Math.floor(Math.random() * 9) + 1;
            selectedStars.add(randStar);
        }

        document.querySelectorAll('.number-grid button').forEach(btn => {
            if (selectedNumbers.has(parseInt(btn.getAttribute("data-value")))) {
                btn.classList.add("selected");
            }
        });
        document.querySelectorAll('.star-grid button').forEach(btn => {
            if (selectedStars.has(parseInt(btn.getAttribute("data-value")))) {
                btn.classList.add("selected");
            }
        });
    }

    function prepareTicket() {
        if (selectedNumbers.size !== 5 || selectedStars.size !== 2) {
            alert("Veuillez sélectionner 5 numéros et 2 étoiles.");
            return false;
        }

        const ticket = [...selectedNumbers].join(",") + " | " + [...selectedStars].join(",");
        document.getElementById("ticket").value = ticket;
    }
</script>

</body>
</html>
