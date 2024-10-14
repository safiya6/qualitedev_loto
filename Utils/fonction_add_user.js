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

    document.getElementById("edit-id_joueur").value = id_joueur;
    document.getElementById("edit-pseudo").value = pseudo;

    document.querySelectorAll('.number-grid button, .star-grid button').forEach(btn => btn.classList.remove("selected"));
    selectedNumbers.clear();
    selectedStars.clear();

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

function deleteUser(id_joueur) {
    if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return;

    fetch(`?controller=partie&action=deleteUser&id_joueur=${id_joueur}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPlayers();
        } else {
            alert("Erreur lors de la suppression du joueur.");
        }
    })
    .catch(error => console.error("Erreur AJAX:", error));
}

function submitEditForm() {
    const id_joueur = document.getElementById("edit-id_joueur").value;
    const pseudo = document.getElementById("edit-pseudo").value;
    const numbers = [...selectedNumbers].join(",");
    const stars = [...selectedStars].join(",");

    fetch("?controller=partie&action=editUser", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `id_joueur=${id_joueur}&pseudo=${encodeURIComponent(pseudo)}&numbers=${numbers}&stars=${stars}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("edit-form-container").style.display = "none";
            loadPlayers();
        } else {
            document.getElementById("error-message").innerText = data.message;
            document.getElementById("error-message").style.display = "block";
        }
    })
    .catch(error => console.error("Erreur:", error));
}

function loadPlayers() {
    fetch("?controller=partie&action=selectRandomJoueurs")
    .then(response => response.text())
    .then(html => {
        document.querySelector(".data-rows").innerHTML = html;
    })
    .catch(error => console.error("Erreur lors du chargement des joueurs:", error));
}
