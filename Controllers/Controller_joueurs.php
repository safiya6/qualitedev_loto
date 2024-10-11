<?php

class Controller_joueurs extends Controller
{
    /**
     * Action par défaut
     */
    public function action_default()
    {
        $this->render("select_users");
    }

    /**
     * Action pour sélectionner des utilisateurs aléatoirement en fonction du nombre donné.
     */
    public function action_selectRandomUsers()
    {
        // Vérifier si le nombre est reçu via POST
        if (isset($_POST['nombre'])) {
            $nombre = intval($_POST['nombre']);

            // Appeler la fonction du modèle pour sélectionner des utilisateurs aléatoires
            $model = Model::getModel();
            $randomUsers = $model->selectRandomJoueurs($nombre);

            // Afficher les utilisateurs sélectionnés dans la vue
            $this->render("select_users", ['users' => $randomUsers]);
        } else {
            // Si aucun nombre n'est fourni, afficher une erreur
            $this->action_error("Nombre non spécifié pour la sélection d'utilisateurs.");
        }
    }
}

?>
