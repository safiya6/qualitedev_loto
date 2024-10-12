<?php

class Controller_joueurs extends Controller
{
    /**
     * Action par défaut : affiche le formulaire pour ajouter un utilisateur.
     */
    public function action_default()
    {
        $model = Model::getModel();
        $joueurs = $model->getJoueurs(); // Nouvelle méthode pour obtenir la liste des joueurs
        $this->render("add_user", ['joueurs' => $joueurs]);
    }

    /**
     * Action pour ajouter un utilisateur avec le pseudo fourni.
     */
    public function action_addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pseudo']) && !empty($_POST['numbers']) && !empty($_POST['stars'])) {
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));
    
            // Validation côté serveur
            if (count($numbers) === 5 && count($stars) === 2) {
                // Tri des numéros et étoiles pour une uniformité
                sort($numbers);
                sort($stars);
    
                // Génération du ticket sous format "1-2-3-4-5 | 1-2"
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
                $success = $model->addJoueur($pseudo, $ticket);
    
                $message = $success ? "Utilisateur ajouté avec succès !" : "Erreur lors de l'ajout de l'utilisateur.";
                $this->render("add_user", ['message' => $message]);
            } else {
                $this->action_error("Sélection incorrecte de numéros ou d'étoiles.");
            }
        } else {
            $this->action_error("Pseudo, numéros ou étoiles non spécifiés.");
        }
    }
    
    
}

?>

