<?php
session_start();

class Controller_joueurs extends Controller
{
    
        public function action_default()
        {
            $model = Model::getModel();
            $joueurs = $model->selectAllJoueurs_creer();
            $_SESSION['currentPlayers'] = $joueurs; // Remplir les joueurs dans la session
            $message = $_SESSION['message'] ?? null;
            unset($_SESSION['message']); // Efface le message après affichage
            $this->render("add_user", ['joueurs' => $joueurs, 'message' => $message]);
        }
    
        // Autres fonctions restent inchangées
    
    public function action_addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification des données du formulaire
            if (empty($_POST['pseudo']) || empty($_POST['numbers']) || empty($_POST['stars'])) {
                $_SESSION['message'] = "Erreur : Pseudo, numéros ou étoiles non spécifiés.";
                header("Location: ?controller=joueurs");
                exit();
            }

            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));

            // Validation du pseudo
            if (strlen($pseudo) < 5 || strlen($pseudo) > 15 || preg_match_all('/\d/', $pseudo) > 2) {
                $_SESSION['message'] = "Erreur : Le pseudo doit contenir entre 5 et 15 caractères et un maximum de 2 chiffres.";
                header("Location: ?controller=joueurs");
                exit();
            }

            // Validation des numéros et étoiles
            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);

                $model = Model::getModel();
                $id_joueur = $_POST['id_joueur'] ?? null;

                // Ajout ou modification du joueur
                $success = $id_joueur 
                    ? $model->updateJoueurs_creer($id_joueur, $pseudo, $ticket)
                    : $model->insertJoueurs_creer($pseudo, $ticket);

                if (!$success) {
                    $_SESSION['message'] = "Erreur : pseudo ou ticket déjà existant.";
                }

                header("Location: ?controller=joueurs");
                exit();
            } else {
                $_SESSION['message'] = "Sélection incorrecte de numéros ou d'étoiles.";
                header("Location: ?controller=joueurs");
                exit();
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
?>
