<?php

class Controller_joueurs extends Controller
{
    /**
     * Action par défaut : affiche le formulaire pour ajouter un utilisateur.
     */
    public function action_default()
    {
        $model = Model::getModel();
        $joueurs = $model->selectAllJoueurs_creer(); // Nouvelle méthode pour obtenir la liste des joueurs
        $this->render("add_user", ['joueurs' => $joueurs]);
    }

    /**
     * Action pour ajouter un utilisateur avec le pseudo fourni.
     */
    public function action_addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));
    
            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
                $id_joueur = $_POST['id_joueur'] ?? null;
    
                $success = $id_joueur 
                    ? $model->updateJoueurs_creer($id_joueur, $pseudo, $ticket)
                    : $model->insertJoueurs_creer($pseudo, $ticket);
    
                // Message en cas d'erreur
                if (!$success) {
                    $message = "Erreur : pseudo ou ticket déjà existant.";
                    $joueurs = $model->selectAllJoueurs_creer();
                    $this->render("add_user", ['message' => $message, 'joueurs' => $joueurs]);
                    return;
                }
            } else {
                $message = "Sélection incorrecte de numéros ou d'étoiles.";
                $joueurs = $model->selectAllJoueurs_creer();
                $this->render("add_user", ['message' => $message, 'joueurs' => $joueurs]);
                return;
            }
        }
    
        // Chargement normal de la page
        $joueurs = Model::getModel()->selectAllJoueurs_creer();
        $this->render("add_user", ["joueurs" => $joueurs]);
    }
    
    
    
    
    
    

        public function action_deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_joueur'])) {
            $id_joueur = intval($_POST['id_joueur']);
            $model = Model::getModel();
            $model->deleteJoueurs_creer($id_joueur); // Méthode du modèle pour supprimer un joueur
        }
        // Redirection pour éviter la resoumission de formulaire en rechargeant la page
        header("Location: ?controller=joueurs");
        exit();
    }

   

}

?>
