
<?php require_once "view_begin.php" ; ?>
<div class="container">
    <!-- Liste des utilisateurs -->
    <div class="users-list">
        <h3>joueurs inscrits</h3>
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
        <?php var_dump($joueurs); ?>
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
