<?php
session_start();

class Controller_partie extends Controller
{
    
    public function action_selectRandomJoueurs()
{
    $model = Model::getModel();

    // Vérifie si un nombre a été spécifié dans le formulaire, sinon utilise 10 par défaut
    $nombre = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 10;

    // Limite le nombre à 1-100 pour éviter des valeurs en dehors de la plage
    $nombre = max(1, min($nombre, 100));

    // Récupère les joueurs
    $joueurs = $model->selectRandomJoueurs_pred($nombre);

    // Affiche les joueurs
    $this->render("display_joueurs", ['joueurs' => $joueurs]);
}
}
?>