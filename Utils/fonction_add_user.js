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

function populateForm(joueur) {
    document.getElementById("pseudo").value = joueur.pseudo;
    document.getElementById("numbers").value = joueur.ticket; // Adjust depending on input structure

    // Update the form action for editing
    const form = document.querySelector(".form-container form");
    form.action = "?controller=joueurs&action=editUser";
    if (!document.getElementById("edit-id")) {
        form.insertAdjacentHTML('beforeend', `<input type="hidden" id="edit-id" name="id_joueur" value="${joueur.id_joueur}">`);
    } else {
        document.getElementById("edit-id").value = joueur.id_joueur;
    }
}
