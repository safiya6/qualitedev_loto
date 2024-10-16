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
    
    // Debugging information
    console.log("ID Joueur:", document.getElementById("edit-id_joueur").value);
    console.log("Pseudo:", document.getElementById("edit-pseudo").value);
    console.log("Type Joueur:", document.getElementById("type-joueur").value);
    console.log("Numéros:", document.getElementById("numbers").value);
    console.log("Étoiles:", document.getElementById("stars").value);

    return true;
}



function showEditForm(id_joueur, pseudo, ticket, type_joueur) {
    // Remplit les champs cachés
    document.getElementById("edit-id_joueur").value = id_joueur;
    document.getElementById("edit-pseudo").value = pseudo;
    document.getElementById("type-joueur").value = type_joueur; // Définit le type de joueur

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

    // Affiche le formulaire de modification
    document.getElementById("overlay").style.display = "block";
    document.getElementById("edit-form-container").style.display = "block";
}


function hideEditForm() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("edit-form-container").style.display = "none";
}