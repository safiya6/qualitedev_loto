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
    
            // Validation côté serveur
            if (count($numbers) === 5 && count($stars) === 2) {
                // Tri des numéros et étoiles pour une uniformité
                sort($numbers);
                sort($stars);
    
                // Génération du ticket sous format "1-2-3-4-5 | 1-2"
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
                $success = $model->insertJoueurs_creer($pseudo, $ticket); // Nouvelle méthode pour insérer un joueur
    
                $message = $success ? "Utilisateur ajouté avec succès !" : "Erreur lors de l'ajout de l'utilisateur.";
                $joueurs = $model->selectAllJoueurs_creer(); // Rafraîchit la liste des joueurs après ajout
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

    public function action_editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_joueur']) && !empty($_POST['pseudo']) && !empty($_POST['numbers']) && !empty($_POST['stars'])) {
            $id_joueur = (int)$_POST['id_joueur'];
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));

            // Validation côté serveur pour s'assurer du bon nombre de numéros et étoiles
            if (count($numbers) === 5 && count($stars) === 2) {
                // Génération du ticket sous format "1-2-3-4-5 | 1-2"
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);

                // Mise à jour dans la base de données
                $model = Model::getModel();
                $success = $model->updateJoueur($id_joueur, $pseudo, $ticket);

                // Message de succès ou d'erreur
                $message = $success ? "Utilisateur mis à jour avec succès !" : "Erreur lors de la mise à jour de l'utilisateur.";
                $this->render("add_user", ['message' => $message]);
            } else {
                $this->action_error("Sélection incorrecte de numéros ou d'étoiles.");
            }
        } else {
            $this->action_error("Informations manquantes pour la mise à jour.");
        }
    }


}

?>
