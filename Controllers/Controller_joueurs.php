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
            // Vérification et traitement des données de formulaire
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));
    
            // Validation des numéros et des étoiles
            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);
    
                $model = Model::getModel();
                $id_joueur = $_POST['id_joueur'] ?? null;
    
                // Insertion ou mise à jour
                $success = $id_joueur 
                    ? $model->updateJoueurs_creer($id_joueur, $pseudo, $ticket)
                    : $model->insertJoueurs_creer($pseudo, $ticket);
    
                // Réponse JSON en cas de duplication
                if (!$success) {
                    echo json_encode(["status" => "error", "message" => "Erreur : pseudo ou ticket déjà existant."]);
                    exit();
                }
    
                echo json_encode(["status" => "success"]);
                exit();
            } else {
                echo json_encode(["status" => "error", "message" => "Sélection incorrecte de numéros ou d'étoiles."]);
                exit();
            }
        }
    
        // Si requête en GET, on affiche la page sans JSON
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
