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

function populateForm(id_joueur, pseudo, ticket) {
    console.log("Modification du joueur :", id_joueur);

    // Remplit le champ caché avec l'identifiant du joueur
    document.getElementById("id_joueur").value = id_joueur;
    document.getElementById("pseudo").value = pseudo;

    // Réinitialise les sélections et vide les ensembles
    document.querySelectorAll('.number-grid button, .star-grid button').forEach(btn => btn.classList.remove("selected"));
    selectedNumbers.clear();
    selectedStars.clear();

    const [numbers, stars] = ticket.split(" | ");

    // Sélectionne les numéros et ajoute-les à selectedNumbers
    numbers.split("-").forEach(num => {
        const button = document.querySelector(`.number-grid button[data-value="${num}"]`);
        if (button) {
            button.classList.add("selected");
            selectedNumbers.add(parseInt(num));
        }
    });

    // Sélectionne les étoiles et ajoute-les à selectedStars
    stars.split("-").forEach(star => {
        const button = document.querySelector(`.star-grid button[data-value="${star}"]`);
        if (button) {
            button.classList.add("selected");
            selectedStars.add(parseInt(star));
        }
    });
}

function generateRandomPseudo() {
    const letters = "abcdefghijklmnopqrstuvwxyz";
    const numbers = "0123456789";
    let pseudo = "";

    // Génère une partie aléatoire de lettres
    const letterLength = Math.floor(Math.random() * (15 - 5 + 1) + 3); // entre 3 et 13 lettres
    for (let i = 0; i < letterLength; i++) {
        pseudo += letters.charAt(Math.floor(Math.random() * letters.length));
    }

    // Ajoute jusqu'à 2 chiffres à la fin du pseudo
    const numLength = Math.floor(Math.random() * 3); // entre 0 et 2 chiffres
    for (let i = 0; i < numLength; i++) {
        pseudo += numbers.charAt(Math.floor(Math.random() * numbers.length));
    }

    // Remplit l'input avec le pseudo généré
    document.getElementById("pseudo").value = pseudo;
}


function showEditForm(id_joueur, pseudo, ticket, type_joueur) {
    // Remplit le champ caché avec l'identifiant du joueur
    document.getElementById("edit-id_joueur").value = id_joueur;
    document.getElementById("edit-pseudo").value = pseudo;
    document.getElementById("type-joueur").value = type_joueur;

    // Réinitialise les sélections et vide les ensembles
    document.querySelectorAll('.number-grid button, .star-grid button').forEach(btn => btn.classList.remove("selected"));
    selectedNumbers.clear();
    selectedStars.clear();

    const [numbers, stars] = ticket.split(" | ");

    // Sélectionne les numéros et ajoute-les à selectedNumbers
    numbers.split("-").forEach(num => {
        const button = document.querySelector(`.number-grid button[data-value="${num}"]`);
        if (button) {
            button.classList.add("selected");
            selectedNumbers.add(parseInt(num));
        }
    });

    // Sélectionne les étoiles et ajoute-les à selectedStars
    stars.split("-").forEach(star => {
        const button = document.querySelector(`.star-grid button[data-value="${star}"]`);
        if (button) {
            button.classList.add("selected");
            selectedStars.add(parseInt(star));
        }
    });

    document.getElementById("edit-form-container").style.display = "block";
}


// Fonction pour sélectionner un nombre aléatoire de joueurs cochés
function selectRandom() {
    const checkboxes = Array.from(document.querySelectorAll('.select-checkbox')).filter(cb => cb.checked);
    const nombre = document.getElementById('nombre').value;

    if (nombre > checkboxes.length) {
        alert("Nombre sélectionné dépasse les joueurs disponibles !");
        return;
    }

    // Réinitialisation des cases cochées
    checkboxes.forEach(cb => cb.checked = false);

    // Sélection aléatoire
    const selected = [];
    while (selected.length < nombre) {
        const index = Math.floor(Math.random() * checkboxes.length);
        if (!selected.includes(index)) {
            selected.push(index);
            checkboxes[index].checked = true;
        }
    }
}

 // Fonction pour cocher toutes les cases
 function selectAllCheckboxes(checkbox) {
    const checkboxes = document.querySelectorAll('.select-checkbox');
    checkboxes.forEach((cb) => cb.checked = checkbox.checked);
}

function deleteUser(id_joueur) {
    // Demande de confirmation avant la suppression
    if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return;
    // Envoi de la requête AJAX en utilisant fetch
    fetch(`?controller=partie&action=deleteUser&id_joueur=${id_joueur}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json()) // Récupère la réponse JSON du serveur
    .then(data => {
        if (data.success) {
            // Si la suppression a réussi, supprimer la ligne correspondante
            const row = document.querySelector(`.data-row[data-id='${id_joueur}']`);
            if (row) row.remove();
        } else {
            alert("Erreur lors de la suppression du joueur.");
        }
    })
    .catch(error => console.error("Erreur AJAX:", error));
}
