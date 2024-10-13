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
    // Décompose le ticket en numéros et étoiles
    const [numberStr, starStr] = ticket.split(" | ");
    const numbers = numberStr.split("-").map(Number);
    const stars = starStr.split("-").map(Number);

    // Met à jour les champs du formulaire
    document.getElementById('id_joueur').value = id_joueur;
    document.getElementById('pseudo').value = pseudo;

    // Réinitialise les sélections actuelles
    selectedNumbers.clear();
    selectedStars.clear();

    // Ajoute les numéros et étoiles du ticket aux ensembles sélectionnés
    numbers.forEach(num => selectedNumbers.add(num));
    stars.forEach(star => selectedStars.add(star));

    // Met à jour l'affichage pour les numéros sélectionnés
    document.querySelectorAll('.number-grid button').forEach(btn => {
        const value = parseInt(btn.getAttribute('data-value'));
        if (selectedNumbers.has(value)) {
            btn.classList.add('selected');
        } else {
            btn.classList.remove('selected');
        }
    });

    // Met à jour l'affichage pour les étoiles sélectionnées
    document.querySelectorAll('.star-grid button').forEach(btn => {
        const value = parseInt(btn.getAttribute('data-value'));
        if (selectedStars.has(value)) {
            btn.classList.add('selected');
        } else {
            btn.classList.remove('selected');
        }
    });
}


