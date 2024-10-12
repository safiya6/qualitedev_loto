<?php

class Controller_joueurs extends Controller
{
    /**
     * Action par défaut : affiche le formulaire pour ajouter un utilisateur.
     */
    public function action_default()
    {
        $this->render("add_user");
    }

    /**
     * Action pour ajouter un utilisateur avec le pseudo fourni.
     */
    public function action_addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pseudo']) && !empty($_POST['ticket'])) {
            $pseudo = trim($_POST['pseudo']);
            $ticket = trim($_POST['ticket']);
    
            error_log("Pseudo: $pseudo, Ticket: $ticket");  // Log de débogage
    
            $model = Model::getModel();
            $success = $model->addJoueur($pseudo, $ticket);
            $message = $success ? "Utilisateur ajouté avec succès !" : "Erreur lors de l'ajout de l'utilisateur.";
            $this->render("add_user", ['message' => $message]);
        } else {
            $this->action_error("Pseudo ou ticket non spécifié.");
        }
    }
    
}

?>

