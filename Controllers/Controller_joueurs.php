<?php
    session_start();

class Controller_joueurs extends Controller
{
    public function action_default()
    {
        $model = Model::getModel();
        $joueurs = $model->selectAllJoueurs_creer(); // Récupérer tous les joueurs
        $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
        unset($_SESSION['message']); // Effacer le message après affichage
        $this->render("add_user", ['joueurs' => $joueurs, 'message' => $message]);
    }

    public function action_addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification des données du formulaire
            if (empty($_POST['pseudo']) || empty($_POST['numbers']) || empty($_POST['stars'])) {
                $this->render("add_user", ['message' => "Erreur : Pseudo, numéros ou étoiles non spécifiés."]);
                return;
            }
    
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));
    
            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
                $id_joueur = $_POST['id_joueur'] ?? null;
    
                if ($id_joueur) {
                    $success = $model->updateJoueurs_creer($id_joueur, $pseudo, $ticket);
                } else {
                    $success = $model->insertJoueurs_creer($pseudo, $ticket);
                }
    
                $message = $success ? "" : "Erreur : pseudo ou ticket déjà existant.";
                $joueurs = $model->selectAllJoueurs_creer();
                $this->render("add_user", ['message' => $message, 'joueurs' => $joueurs]);
            } else {
                $this->render("add_user", ['message' => "Sélection incorrecte de numéros ou d'étoiles."]);
            }
        }
    }
    

    public function action_deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_joueur'])) {
            $id_joueur = intval($_POST['id_joueur']);
            $model = Model::getModel();
            $model->deleteJoueurs_creer($id_joueur);
        }
        header("Location: ?controller=joueurs");
        exit();
    }
}
