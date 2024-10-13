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
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").onsubmit = function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch("?controller=joueurs&action=addUser", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "error") {
                alert(data.message);
            } else {
                location.reload(); // Recharge pour mettre à jour les joueurs sans message de succès
            }
        })
        .catch(error => console.error("Erreur:", error));
    };
});






