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
            if (!$this->validatePseudo($pseudo)) {
                $_SESSION['message'] = "Erreur : Le pseudo doit contenir entre 5 et 15 caractères et un maximum de 2 chiffres.";
                header("Location: ?controller=joueurs");
                exit();
            }

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

                if (!$success) {
                    $_SESSION['message'] = "Erreur : pseudo ou ticket déjà existant.";
                }
                
                // Rediriger pour éviter l'affichage intempestif du message d'erreur
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

    // Fonction de validation du pseudo
    private function validatePseudo($pseudo)
    {
        $lengthValid = strlen($pseudo) >= 5 && strlen($pseudo) <= 15;
        $numbersCount = preg_match_all('/\d/', $pseudo);
        $numbersValid = $numbersCount <= 2;

        return $lengthValid && $numbersValid;
    }
}
