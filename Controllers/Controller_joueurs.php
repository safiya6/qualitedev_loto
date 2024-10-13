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
        $model = Model::getModel();
        $id_joueur = $_POST['id_joueur'] ?? null;
        $pseudo = trim($_POST['pseudo']);
        $numbers = explode(",", trim($_POST['numbers']));
        $stars = explode(",", trim($_POST['stars']));
    
        // Validation du nombre de numéros et d'étoiles
        if (count($numbers) === 5 && count($stars) === 2) {
            sort($numbers);
            sort($stars);
            $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
            if ($id_joueur) {
                // Mise à jour du joueur
                $success = $model->updateJoueur($id_joueur, $pseudo, $ticket);
                $message = $success ? "Utilisateur mis à jour avec succès !" : "Erreur lors de la mise à jour de l'utilisateur.";
            } else {
                // Ajout d'un nouvel utilisateur
                $success = $model->insertJoueurs_creer($pseudo, $ticket);
                $message = $success ? "Utilisateur ajouté avec succès !" : "Erreur lors de l'ajout de l'utilisateur.";
            }
    
            // Rafraîchit la liste des joueurs et rend la vue
            $joueurs = $model->selectAllJoueurs_creer();
            $this->render("add_user", ['message' => $message, 'joueurs' => $joueurs]);
        } else {
            $this->action_error("Sélection incorrecte de numéros ou d'étoiles.");
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
