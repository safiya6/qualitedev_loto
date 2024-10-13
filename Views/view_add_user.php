<div class="container">
    <!-- Message d'erreur -->
    <div id="error-message" class="alert-message"></div>

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
                    
                    <!-- Formulaire de suppression -->
                    <form action="?controller=joueurs&action=deleteUser" method="POST" class="delete-form">
                        <input type="hidden" name="id_joueur" value="<?= $joueur['id_joueur'] ?>">
                        <button type="submit" class="delete-button">
                            <!-- Icône de poubelle SVG -->
                        </button>
                    </form>

                    <!-- Bouton de modification -->
                    <button type="button" class="edit-button" 
                        onclick="populateForm(<?= $joueur['id_joueur'] ?>, '<?= htmlspecialchars($joueur['pseudo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($joueur['ticket'], ENT_QUOTES) ?>')">
                        <!-- Icône de crayon SVG -->
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire d'ajout d'utilisateur -->
    <div class="form-container">
        <h2>Ajouter un Utilisateur</h2>
        <form action="?controller=joueurs&action=addUser" method="POST" onsubmit="return prepareTicket()">
            <input type="hidden" id="id_joueur" name="id_joueur">
            <div class="form-group">
                <label for="pseudo">Choisissez un pseudo :</label>
                <div class="input-group">
                    <input type="text" id="pseudo" name="pseudo" required>
                    <button type="button" id="generate-pseudo" onclick="generateRandomPseudo()">
                        🎲
                    </button>
                </div>
            </div>

            <!-- Grille de numéros -->
            <label>Choisissez vos numéros :</label>
            <div class="number-grid">
                <?php for ($i = 1; $i <= 49; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'number')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <!-- Grille d'étoiles -->
            <label>Choisissez vos étoiles :</label>
            <div class="star-grid">
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <button type="button" onclick="toggleSelection(this, 'star')" data-value="<?= $i ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>

            <!-- Bouton de génération aléatoire -->
            <button type="button" class="generate-button" onclick="generateRandomSelection()">
                <i>🎲</i> Générer aléatoirement
            </button>

            <input type="hidden" id="numbers" name="numbers">
            <input type="hidden" id="stars" name="stars">

            <!-- Bouton de soumission -->
            <button type="submit" class="generate-button">Ajouter l'utilisateur</button>
        </form>
    </div>
</div>
