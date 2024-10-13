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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pseudo']) && !empty($_POST['numbers']) && !empty($_POST['stars'])) {
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));
    
            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
                $id_joueur = $_POST['id_joueur'] ?? null; // Vérifie si c'est une mise à jour
    
                if ($id_joueur) {
                    // Mise à jour d'un joueur existant
                    $success = $model->updateJoueurs_creer($id_joueur, $pseudo, $ticket);
                } else {
                    // Insertion d'un nouveau joueur
                    $success = $model->insertJoueurs_creer($pseudo, $ticket);
                }
    
                if ($success) {
                    $message = $id_joueur ? "Utilisateur mis à jour avec succès !" : "Utilisateur ajouté avec succès !";
                } else {
                    $message = "Erreur : pseudo ou ticket déjà existant.";
                }
    
                $joueurs = $model->selectAllJoueurs_creer();
                $this->render("add_user", ['message' => $message, 'joueurs' => $joueurs]);
            } else {
                $this->action_error("Sélection incorrecte de numéros ou d'étoiles.");
            }
        } else {
            $this->action_error("Pseudo, numéros ou étoiles non spécifiés.");
        }
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
