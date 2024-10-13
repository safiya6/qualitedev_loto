<?php require_once "view_begin.php"; ?>


<div class="container">
    <!-- Liste des utilisateurs -->
    <div class="users-list">
        <h3>Joueurs inscrits</h3>
        <div class="header-row">
            <div class="header-item">Pseudo</div>
            <div class="header-item">Ticket</div>
        </div>
        <div class="data-rows">
            <?php foreach ($joueurs as $joueur): ?>
                <div class="data-row">
                    <div class="user-item"><?= htmlspecialchars($joueur['pseudo']) ?></div>
                    <div class="ticket-item"><?= htmlspecialchars($joueur['ticket']) ?></div>
                    
                    <!-- Delete Button Form -->
                    <form action="?controller=joueurs&action=deleteUser" method="POST" class="delete-form">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <button type="submit" class="delete-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5v-8zM4.118 4a1 1 0 0 1 .82-.4h6.144a1 1 0 0 1 .82.4l.845 1H3.273l.845-1zM1 4.5A.5.5 0 0 1 1.5 4h13a.5.5 0 0 1 .5.5V5h-15v-.5zM2 5.5v9A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-9H2z"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Edit Button -->
                    <button type="button" class="edit-button" 
                        onclick="populateForm(<?= $joueur['id_joueur'] ?>, '<?= htmlspecialchars($joueur['pseudo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($joueur['ticket'], ENT_QUOTES) ?>')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146 0a.5.5 0 0 1 .352.146l2.5 2.5a.5.5 0 0 1 0 .708L13.207 4.646l-3-3L12.146.146A.5.5 0 0 1 12.146 0z"/>
                            <path fill-rule="evenodd" d="M1 13.5V16h2.5l7-7-2.5-2.5-7 7zm.646-.146l7-7L10.5 7.207l-7 7H1v-1.5a.5.5 0 0 1 .146-.354z"/>
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire d'ajout d'utilisateur -->
    <div class="form-container">
    <?php if (isset($message)): ?>
        <div id="error-message" class="error-message">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
        <h2>Ajouter un Utilisateur</h2>
        <form action="?controller=joueurs&action=addUser" method="POST" onsubmit="return prepareTicket()">
            <input type="hidden" id="id_joueur" name="id_joueur">
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

            <input type="hidden" id="numbers" name="numbers">
            <input type="hidden" id="stars" name="stars">

            <button type="submit" class="generate-button">Ajouter l'utilisateur</button>
        </form>

    </div>
</div>
<?php if (isset($message)): ?>
    <script>
        document.getElementById("error-message").style.display = "block";
        document.getElementById("error-message").innerText = <?= json_encode($message) ?>;
    </script>
<?php endif; ?>


<script src="Utils/fonction_add_user.js"></script> 
</body>
</html>
