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


function showEditForm(id, pseudo, ticket) {
    document.getElementById('edit-id_joueur').value = id;
    document.getElementById('edit-pseudo').value = pseudo;

    // Reset selections
    selectedNumbers.clear();
    selectedStars.clear();
    document.querySelectorAll('.number-grid button, .star-grid button').forEach(btn => btn.classList.remove("selected"));

    const [numbers, stars] = ticket.split(" | ");
    numbers.split("-").forEach(num => {
        const button = document.querySelector(`.number-grid button[data-value="${num}"]`);
        if (button) {
            button.classList.add("selected");
            selectedNumbers.add(parseInt(num));
        }
    });

    stars.split("-").forEach(star => {
        const button = document.querySelector(`.star-grid button[data-value="${star}"]`);
        if (button) {
            button.classList.add("selected");
            selectedStars.add(parseInt(star));
        }
    });

    document.getElementById("edit-form-container").style.display = "block";
}










