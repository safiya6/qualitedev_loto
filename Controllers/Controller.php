<?php
namespace App\Controllers;

/**
 * @brief Classe abstraite Controller
 * 
 * Cette classe sert de base pour tous les contrôleurs. Elle gère les actions et le rendu des vues.
 * Les classes filles doivent implémenter la méthode `action_default`.
 */
abstract class Controller
{
    /**
     * Constructeur. Lance l'action correspondante.
     * 
     * Le constructeur vérifie s'il existe dans l'URL un paramètre `action` correspondant à une action
     * du contrôleur. Si c'est le cas, il appelle cette action, sinon il appelle l'action par défaut.
     */
    public function __construct()
    {
        ini_set('memory_limit', '256M'); // Augmentez à 256M ou plus si nécessaire

        if (isset($_GET['action']) && method_exists($this, "action_" . $_GET["action"])) {
            $action = "action_" . $_GET["action"];
            $this->$action();
        } else {
            $this->action_default();
        }
    }

    /**
     * Action par défaut du contrôleur.
     * 
     * Cette méthode doit être définie dans les classes filles.
     */
    abstract public function action_default();

    /**
     * Affiche la vue.
     * 
     * @param string $vue Le nom de la vue à afficher.
     * @param array $data (optionnel) Tableau associatif contenant les données à passer à la vue.
     * @param string $dossier (optionnel) Le dossier où se trouve la vue.
     * @return void
     */
    protected function render($vue, $data = [], $dossier = '')
    {
        error_log("Rendu de la vue: " . $vue);
        error_log("Données passées à la vue: " . json_encode($data));
        extract($data);
        if (!empty($dossier)) {
            $dossier = $dossier . '/';
        }
        $file_name = "Views/" . $dossier . "view_" . $vue . '.php';
        if (file_exists($file_name)) {
            include $file_name;
        } else {
            $this->action_error("La vue n'existe pas !" . $file_name);
        }
        die();
    }
    // protected function render($vue, $data = [], $dossier = '')
    // {
    //     error_log("Rendu de la vue: " . $vue);
    //     error_log("Données passées à la vue: " . json_encode($data));

    //     extract($data);
    //     if (!empty($dossier)) {
    //         $dossier = $dossier . '/';
    //     }

    //     // Assurez-vous que le chemin vers le fichier de vue est correct
    //     $view_file = "Views/" . $dossier . "view_" . $vue . '.php';
    //     ;

    //     if (file_exists($view_file)) {
    //         require $view_file;
    //     } else {
    //         error_log("Erreur: Vue non trouvée - " . $view_file);
    //         echo "Erreur: Vue non trouvée - " . $view_file;
    //     }
    // }


    /**
     * Méthode affichant une page d'erreur.
     * 
     * @param string $message (optionnel) Le message d'erreur à afficher.
     * @return void
     */
    protected function action_error($message = '')
    {
        $data = [
            'title' => "Error",
            'message' => $message,
        ];
        $this->render("message", $data);
    }
}
?>
